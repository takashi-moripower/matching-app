<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Defines\Defines;

/**
 * Comments Controller
 *
 * @property \App\Model\Table\CommentsTable $Comments
 */
class CommentsController extends AppController {

	/**
	 * Index method
	 *
	 * @return \Cake\Network\Response|null
	 */
	public function index() {
		$this->paginate = [
			'contain' => ['Engineers', 'Enterprises']
		];
		$comments = $this->paginate($this->Comments);

		$this->set(compact('comments'));
		$this->set('_serialize', ['comments']);
	}

	/**
	 * View method
	 *
	 * @param string|null $id Comment id.
	 * @return \Cake\Network\Response|null
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function view($engineer_id, $enterprise_id) {
		$loginUser = $this->_getLoginUser();
		switch ($loginUser['group_id']) {
			case Defines::GROUP_ADMINISTRATOR:
				$flags = Defines::COMMENT_FLAG_SEND_ADMINISTRATOR;
				break;

			case Defines::GROUP_ENGINEER:
				if ($loginUser['engineer_id'] != $engineer_id) {
					$this->Flash->error('アクセス権がありません');
					return $this->redirect(['controller' => 'users', 'aciton' => 'login']);
				}
				$flags = Defines::COMMENT_FLAG_SEND_ENGINEER | Defines::COMMENT_FLAG_READ_ENGINEER;
				break;

			case Defines::GROUP_ENTERPRISE_PREMIUM:
			case Defines::GROUP_ENTERPRISE_FREE:
				if ($loginUser['enterprise_id'] != $enterprise_id) {
					$this->Flash->error('アクセス権がありません');
					return $this->redirect(['controller' => 'users', 'aciton' => 'login']);
				}
				$flags = Defines::COMMENT_FLAG_SEND_ENTERPRISE | Defines::COMMENT_FLAG_READ_ENTERPRISE;
				break;

			default:
				$this->Flash->error('アクセス権がありません');
				return $this->redirect(['controller' => 'users', 'aciton' => 'login']);
		}


		if ($this->request->is(['post', 'put', 'patch'])) {
			$new_comment = $this->Comments->newEntity($this->request->data);
			if ($result = $this->Comments->save($new_comment)) {
				$this->Flash->success('コメントは正常に保存されました');
			} else {
				$this->Flash->error('コメントの保存に失敗');
				debug($new_comment);
			}
		}

		$comments = $this->Comments->find()
				->where(['enterprise_id' => $enterprise_id, 'engineer_id' => $engineer_id])
				->contain(['Engineers' => ['Users']])
				->contain(['Enterprises' => ['Users']])
				;

		$this->set(compact('comments' , 'flags' , 'engineer_id' , 'enterprise_id' ));
	}

	/**
	 * Add method
	 *
	 * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
	 */
	public function add() {
		$comment = $this->Comments->newEntity();
		if ($this->request->is('post')) {
			$comment = $this->Comments->patchEntity($comment, $this->request->data);
			if ($this->Comments->save($comment)) {
				$this->Flash->success(__('The comment has been saved.'));

				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('The comment could not be saved. Please, try again.'));
			}
		}
		$engineers = $this->Comments->Engineers->find('list', ['limit' => 200]);
		$enterprises = $this->Comments->Enterprises->find('list', ['limit' => 200]);
		$this->set(compact('comment', 'engineers', 'enterprises'));
		$this->set('_serialize', ['comment']);
	}

	/**
	 * Edit method
	 *
	 * @param string|null $id Comment id.
	 * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function edit($id = null) {
		$comment = $this->Comments->get($id, [
			'contain' => []
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$comment = $this->Comments->patchEntity($comment, $this->request->data);
			if ($this->Comments->save($comment)) {
				$this->Flash->success(__('The comment has been saved.'));

				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('The comment could not be saved. Please, try again.'));
			}
		}
		$engineers = $this->Comments->Engineers->find('list', ['limit' => 200]);
		$enterprises = $this->Comments->Enterprises->find('list', ['limit' => 200]);
		$this->set(compact('comment', 'engineers', 'enterprises'));
		$this->set('_serialize', ['comment']);
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id Comment id.
	 * @return \Cake\Network\Response|null Redirects to index.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function delete($id = null) {
		$this->request->allowMethod(['post', 'delete']);
		$comment = $this->Comments->get($id);
		if ($this->Comments->delete($comment)) {
			$this->Flash->success(__('The comment has been deleted.'));
		} else {
			$this->Flash->error(__('The comment could not be deleted. Please, try again.'));
		}

		return $this->redirect(['action' => 'index']);
	}

}
