<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Defines\Defines;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Cake\Utility\Hash;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController {

	public function initialize() {
		parent::initialize();
		$this->loadComponent('TakashiMoripower/CakeOpauth.Opauth');
	}

	public function beforeFilter(Event $event) {
		parent::beforeFilter($event);
		$this->Auth->allow(['login', 'add']);
	}

	/**
	 * Index method
	 *
	 * @return \Cake\Network\Response|null
	 */
	public function index() {
		$this->paginate = [
			'contain' => ['Groups']
		];
		$users = $this->paginate($this->Users);

		$this->set(compact('users'));
		$this->set('_serialize', ['users']);
	}

	/**
	 * View method
	 *
	 * @param string|null $id User id.
	 * @return \Cake\Network\Response|null
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function view($id = null) {
		$user = $this->Users->get($id, [
			'contain' => ['Groups', 'Comments', 'Enterprises', 'Informations']
		]);

		$this->set('user', $user);
		$this->set('_serialize', ['user']);
	}

	public function editSelf() {
		$id = $this->Auth->user('id');
		$user = $this->Users->get($id);
		return $this->_edit($user, ['action' => 'editSelf']);
	}

	public function edit($id) {
		$user = $this->Users->get($id);
		return $this->_edit($user, ['action' => 'index']);
	}

	/**
	 * Edit method
	 *
	 * @param string|null $id User id.
	 * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	protected function _edit($user, $redirect) {

		if ($this->request->is(['patch', 'post', 'put'])) {
			$user = $this->Users->patchEntity($user, $this->request->data);
			if ($this->Users->save($user)) {
				$this->Flash->success(__('ユーザー情報は正常に保存されました'));
				if (!empty($redirect)) {
					return $this->redirect($redirect);
				} else {
					return;
				}
			} else {
				$this->Flash->error('ユーザー情報の保存に失敗');
			}
		}
		$this->set(compact('user'));

		$this->render('edit');
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id User id.
	 * @return \Cake\Network\Response|null Redirects to index.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function delete($id = null) {
		$this->request->allowMethod(['post', 'delete']);
		$user = $this->Users->get($id);
		if ($this->Users->delete($user)) {
			$this->Flash->success(__('The user has been deleted.'));
		} else {
			$this->Flash->error(__('The user could not be deleted. Please, try again.'));
		}
		return $this->redirect(['action' => 'index']);
	}

	public function login() {
		$this->Auth->logout();
		if (!$this->request->is('post')) {
			return;
		}

		$user = $this->Auth->identify();

		if (!$user) {
			$this->Flash->error(__('Invalid username or password, try again'));
			return;
		}

		return $this->_setLoginUser($user['id']);
	}

	protected function _setLoginUser($user_id) {

		$user = $this->Users->getLoginData($user_id);
		$this->Auth->setUser($user);
		$this->redirect(['controller' => 'home', 'action' => 'index']);
	}

	public function logout() {
		return $this->redirect($this->Auth->logout());
	}

	/**
	 * SNSログイン　受付
	 * 細かい処理はプラグインに投げる
	 * @param type $strategy
	 */
	public function loginSns($strategy) {
		return $this->Opauth->call($strategy, ['controller' => 'users', 'action' => 'snsCallback', 'login', $strategy]);
	}

	/**
	 * SNSログイン　データ処理
	 * @param type $strategy
	 * @param type $auth
	 * @return type
	 */
	protected function _loginSnsCallback($strategy, $auth) {
		$user = $this->Users->find()
				->where([$strategy => $auth['uid']])
				->first();

		if (empty($user)) {
			$this->Flash->default("この{$strategy}アカウントは　EngineerMatchingSystemに登録されていません");
			return $this->_addSnsCallback($strategy, $auth);
		}

		return $this->_setLoginUser($user->id);
	}

	/**
	 * ログイン中のアカウントとSNSを関連づける処理
	 * @param type $strategy
	 * @return type
	 */
	public function linkSns($strategy) {
		return $this->Opauth->call($strategy, ['controller' => 'users', 'action' => 'snsCallback', 'link', $strategy]);
	}

	/**
	 * SNS関連づけ　データ処理
	 * @param type $strategy
	 * @param type $auth
	 * @return type
	 */
	protected function _linkSnsCallback($strategy, $auth) {
		$provider = mb_strtolower($auth['provider']);

		$user_id = $this->Auth->user('id');

		if ($this->Users->exists([$provider => $auth['uid']])) {
			$this->Flash->error("この{$provider}アカウントは、既に登録済みです");
		} else {
			$user = $this->Users->get($user_id);
			$user->{$provider} = $auth['uid'];
			$this->Users->save($user);
			$this->Flash->success("{$provider}の情報が正常に登録されました");
		}

		return $this->redirect(['action' => 'editSelf']);
	}

	/**
	 * SNS関連づけの解除
	 * @param type $id
	 * @param type $strategy
	 * @return type
	 */
	public function unLinkSns($id, $strategy) {
		$strategies = \Cake\Core\Configure::read('SNS');

		if (!in_array($strategy, $strategies)) {
			$this->Flash->error('不正なパラメーター');
			return $this->redirect(['action' => 'index']);
		}

		$user = $this->Users->get($id);
		$user->{$strategy} = NULL;
		$this->Users->save($user);

		$this->Flash->success("{$strategy}の情報が正常に削除されました");
		return $this->redirect(['action' => 'editSelf']);
	}

	/**
	 * SNSから認証を受けて情報を取得した後の処理
	 * @param type $auth
	 * @return type
	 */
	public function snsCallback($action, $strategy) {
		$auth = $this->request->session()->read('opauth.auth');

		if (empty($auth['provider'])) {
			$this->Flash->error("{$strategy} 認証に失敗");
			return $this->redirect(['action' => 'login']);
		}

		$function_name = "_{$action}SnsCallback";
		if (method_exists($this, $function_name)) {
			return $this->{$function_name}($strategy, $auth);
		}
	}

	public function debug() {
		$data = \Cake\Routing\Router::url(['plugin' => 'TakashiMoripower/CakeOpauth', 'controller' => 'auth', 'action' => 'auth', 'twitter']);

		$this->set('data', $data);
		$this->render('/Common/debug');
	}

	/**
	 * SNS登録受付
	 * 細かい処理はプラグインに投げる
	 * @param type $strategy
	 */
	public function addSns($strategy) {
		return $this->Opauth->call($strategy, ['controller' => 'users', 'action' => 'snsCallback', 'add', $strategy]);
	}

	protected function _addSnsCallback($strategy, $auth) {

		if ($this->Users->exists([ $strategy => $auth['uid']])) {
			$this->Flash->error("この{$strategy}アカウントは登録済みです");
			return $this->redirect(['controller' => 'users', 'action' => 'login']);
		}

		$user_data = [
			'name' => $auth['info']['name'],
			$strategy => $auth['uid'],
			'group_id' => Defines::GROUP_ENGINEER,
			'engineer' => [],
			'expunge' => Defines::USER_EXPUNGE_FALSE,
		];


		//twitterはemailを返さないので　メールアドレスの入力を求める
		if ($strategy == 'twitter') {
			$user_data['expunge'] = Defines::USER_EXPUNGE_TRUE;
		} else {
			if ($this->Users->exists([ 'email' => $auth['info']['email']])) {
				$this->Flash->error("このメールアドレスは登録済みです");
				return $this->redirect(['controller' => 'users', 'action' => 'login']);
			}

			$user_data['email'] = $auth['info']['email'];
		}

		$user = $this->Users->newEntity($user_data, ['associated' => ['Engineers']]);

		$this->set('user', $user);
		$this->set('action', ['controller' => 'users', 'action' => 'addEngineer']);
		$this->render('edit');
	}

	public function addEngineer() {
		$user = $this->Users->newEntity([
			'group_id' => Defines::GROUP_ENGINEER,
			'engineer' => [],
			'expunge' => 1,
				]
				, ['associated' => ['Engineers']]
		);

		if ($this->request->is(['patch', 'post', 'put'])) {
			$user = $this->Users->patchEntity($user, $this->request->data);
			if ($this->Users->save($user)) {
				if ($user->expunge) {
					$this->Users->setChecker($user);
					return $this->redirect(['action' => 'check']);
				} else {
					return $this->_setLoginUser($user->id);
				}
			} else {
				$this->Flash->error('ユーザー情報の保存に失敗');

				if ($user->invalid('email')) {
					if (Hash::get($user->errors(), 'email.unique', NULL)) {
						$this->Flash->error('このメールアドレスは登録済みです');
					} else {
						$this->Flash->error('メールアドレスが無効です');
					}
				}
			}
		}
		$this->set(compact('user'));

		$this->render('edit');
	}

	public function check($code = NULL) {
		if (empty($code)) {
			return;
		}

		$opt = TableRegistry::get('Options')->find()
				->where(['content' => $code])
				->first();

		$matches = [];
		if (empty($opt) || !preg_match('/mailAliveCheck.([0-9]+)/', $opt->title, $matches)) {
			$this->Flash->error('無効なコード');
			return $this->redirect(['controller' => 'users', 'action' => 'login']);
		}

		$user_id = $matches[1];
		$user = $this->Users->get($user_id);

		if (empty($user)) {
			$this->Flash->error('無効なコード');
			return $this->redirect(['controller' => 'users', 'action' => 'login']);
		}

		$user->expunge = 0;
		$this->Users->save($user);

		$this->Flash->success('技術者情報は正常に登録されました');
		return $this->_setLoginUser($user_id);
	}

}
