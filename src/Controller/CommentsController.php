<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Defines\Defines;
use Cake\Utility\Hash;
use Cake\ORM\TableRegistry;

/**
 * Comments Controller
 *
 * @property \App\Model\Table\CommentsTable $Comments
 */
class CommentsController extends AppController {

	public $paginate = [
		'order' => ['last_modified' => 'desc'],
		'sortWhitelist' => [
			'id',
			'engineer_name',
			'enterprise_name',
			'content',
			'read_engineer',
			'raad_enterprise',
			'direction',
			'count',
			'modified',
		],
		'contain' => [],
	];

	public function initialize() {
		parent::initialize();
		$this->_loadSearchComponents();
	}

	/**
	 * Index method
	 *
	 * @return \Cake\Network\Response|null
	 */
	public function index() {
		switch ($this->_getLoginUser('group_id')) {
			case Defines::GROUP_ADMINISTRATOR:
				return $this->_indexAdmin();

			case Defines::GROUP_ENGINEER:
				return $this->_indexEngineer();

			case Defines::GROUP_ENTERPRISE_PREMIUM:
			case Defines::GROUP_ENTERPRISE_FREE:
				return $this->_indexEnterprise();
		}
	}

	protected function _indexEnterprise() {

		$enterprise_id = $this->_getLoginUser('enterprise_id');

		$query = $this->Comments
				->find('collection')
				->where(['enterprise_id' => $enterprise_id])
				->find('search', $this->Comments->filterParams($this->request->data))
		;
		$comments = $this->paginate($query);

		$this->set(compact('comments'));
		$this->set('_serialize', ['comments']);
		$this->render('indexEnterprise');
	}

	protected function _indexEngineer() {

		$engineer_id = $this->_getLoginUser('engineer_id');

		$query = $this->Comments
				->find('collection')
				->where(['engineer_id' => $engineer_id])
		;

		$comments = $this->paginate($query);

		$this->set(compact('comments'));
		$this->set('_serialize', ['comments']);
		$this->render('indexEngineer');
	}

	protected function _indexAdmin() {
		$this->paginate = [
			'contain' => ['Engineers' => ['Users'], 'Enterprises' => ['Users']]
		];
		$comments = $this->paginate($this->Comments);

		$this->set(compact('comments'));
		$this->set('_serialize', ['comments']);
		$this->render('index');
	}

	/**
	 * View method
	 *
	 * @param string|null $id Comment id.
	 * @return \Cake\Network\Response|null
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function view($engineer_id, $enterprise_id) {

		if (!$this->_checkAuth($engineer_id, $enterprise_id)) {
			$this->Flash->error('アクセス権がありません');
			return $this->redirect(['controller' => 'users', 'action' => 'login']);
		}

		if ($this->request->is(['post', 'put', 'patch'])) {
			$new_comment = $this->Comments->newEntity($this->request->data);

			if ($this->_checkRepeat($engineer_id, $enterprise_id, $new_comment)) {
				if ($result = $this->Comments->save($new_comment)) {
					$this->Flash->success('コメントは正常に送信されました');
					$this->_setContact($new_comment);
				} else {
					$this->Flash->error('コメントの送信に失敗');
				}
			} else {
				$this->Flash->error('同じ内容のコメントは連続して送信できません');
			}
		}

		$comments = $this->Comments->find()
				->where(['enterprise_id' => $enterprise_id, 'engineer_id' => $engineer_id]);

		$enterprise = TableRegistry::get('enterprises')->get($enterprise_id, ['contain' => 'Users']);
		$engineer = TableRegistry::get('engineers')->get($engineer_id, ['contain' => 'Users']);

		$new_comment = $this->Comments->newEntity([
			'engineer_id' => $engineer_id,
			'enterprise_id' => $enterprise_id,
			'flags' => Defines::getCommentFlagSender($this->_getLoginUser('group_id')),
		]);

		$this->_setReadFlag($comments, $engineer_id, $enterprise_id);

		$this->set(compact('comments', 'engineer', 'enterprise', 'new_comment'));
	}

	protected function _checkRepeat($engineer_id, $enterprise_id, $entity) {
		$last = $this->Comments->find()
				->where(['engineer_id' => $engineer_id, 'enterprise_id' => $enterprise_id])
				->order(['modified' => 'desc', 'id' => 'desc'])
				->first();

		//直前のコメントが存在しない場合true
		if (empty($last)) {
			return true;
		}

		//コメント内容が違う場合はtrue
		if ($last->content != $entity->content) {
			return true;
		}

		//送信者が違う場合はtrue
		if ($last->flags & Defines::COMMENT_FLAG_SEND_MASK != $entity->flags & Defines::COMMENT_FLAG_SEND_MASK) {
			return false;
		}

		return false;
	}

	/**
	 * 既読フラグの更新
	 * @param type $comment
	 */
	protected function _setReadFlag($comments, $engineer_id, $enterprise_id) {
		if ($this->_getLoginUser('engineer_id') == $engineer_id) {
			foreach ($comments as $comment) {
				$comment->flags |= Defines::COMMENT_FLAG_READ_ENGINEER;
				//modifiedを更新しない
				$comment->dirty('modified', true);
				$this->Comments->save($comment);
			}
		}
		if ($this->_getLoginUser('enterprise_id') == $enterprise_id) {
			foreach ($comments as $comment) {
				$comment->flags |= Defines::COMMENT_FLAG_READ_ENTERPRISE;
				//modifiedを更新しない
				$comment->dirty('modified', true);
				$this->Comments->save($comment);
			}
		}
	}

	/**
	 * 関心履歴の更新
	 * @param type $comment
	 */
	protected function _setContact($comment) {
		$table_c = TableRegistry::get('Contacts');

		if ($comment->flags & Defines::COMMENT_FLAG_SEND_ENGINEER) {
			$table_c->setEngineerAccess($comment->engineer_id, $comment->enterprise_id, Defines::CONTACT_RECORD_COMMENT);
			return;
		}
		if ($comment->flags & Defines::COMMENT_FLAG_SEND_ENTERPRISE) {
			$table_c->setEnterpriseAccess($comment->engineer_id, $comment->enterprise_id, Defines::CONTACT_RECORD_COMMENT);
			return;
		}
	}

	/**
	 * アクセス権チェック
	 * @param type $engineer_id
	 * @param type $enterprise_id
	 * @return boolean
	 */
	protected function _checkAuth($engineer_id, $enterprise_id) {
		$loginUser = $this->_getLoginUser();

		if ($loginUser['group_id'] == Defines::GROUP_ADMINISTRATOR) {
			return true;
		}

		if ($loginUser['group_id'] == Defines::GROUP_ENGINEER && $loginUser['engineer_id'] == $engineer_id) {
			return true;
		}

		if (Defines::isEnterprise($loginUser['group_id']) && $loginUser['enterprise_id'] == $enterprise_id) {
			return true;
		}

		return false;
	}

	public function addFile() {

		$this->set('data', $this->request->data);

		$data = $this->request->data;
		$filename = Hash::get($data, 'file.name');
		$tmpname = Hash::get($data, 'file.tmp_name');

		if (empty($filename) || empty($tmpname)) {
			$this->Flash->error('invalid file upload');
			return $this->redirect(['controller' => 'home', 'action' => 'index']);
		}

		$binary = file_get_contents($tmpname);

		$data['file'] = $binary;
		$data['content'] = $filename;

		$entity = $this->Comments->newEntity($data);
		if (!$this->Comments->save($entity)) {
			$this->Flash->error('invalid file save');
			return $this->redirect(['controller' => 'home', 'action' => 'index']);
		}

		$this->redirect(['action' => 'view', $data['engineer_id'], $data['enterprise_id']]);
	}

	public function load($id) {
		$this->autoRender = false;
		$comment = $this->Comments->get($id);
		$img = stream_get_contents($comment->file);

		$reg = "/(.*)(?:\.([^.]+$))/";
		$matches = [];
		$result = preg_match($reg, $comment->content, $matches);

		if ($result && isset($matches[2])) {
			$this->response->type($matches[2]);
		}
		$this->response->body($img);
	}

	public function debug() {

		$query = $this->Comments
				->find('EngineerName')
				->find('EnterpriseName')
				->where(['engineer_id' => 1, 'enterprise_id' => 10])

		;

		$comments = $this->paginate($query);

		$this->set('data', $comments->toArray());
		$this->render('/Common/debug');
	}

}
