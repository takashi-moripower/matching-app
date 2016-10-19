<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Defines\Defines;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class HomeController extends AppController {

	public function beforeFilter(Event $event) {
		parent::beforeFilter($event);
		$this->Auth->allow(['index']);
	}

	public function index() {
		$group_id = $this->Auth->user('group_id');
		$user_id = $this->Auth->user('id');

		if (empty($group_id)) {

			return $this->redirect(['controller' => 'users', 'action' => 'login']);
		}

		switch ($group_id) {
			case Defines::GROUP_ADMINISTRATOR:
				$this->render('admin');
				return;

			case Defines::GROUP_ENTERPRISE_FREE:
			case Defines::GROUP_ENTERPRISE_PREMIUM:
				return $this->_enterprise();

			case Defines::GROUP_ENGINEER:
				return $this->_engineer();
		}
	}

	protected function _enterprise() {
		$group_id = $this->Auth->user('group_id');
		$user_id = $this->Auth->user('id');
		$notices = TableRegistry::get('Notices')->find('active',['group_id'=>$group_id]);
		$enterprise = TableRegistry::get('Enterprises')->find()
				->where(['user_id' => $user_id])
				->first();
		$this->set(compact('enterprise','notices'));
		$this->render('enterprise');
		return;
	}

	protected function _engineer() {
		$group_id = $this->Auth->user('group_id');
		$user_id = $this->Auth->user('id');
		$engineer = TableRegistry::get('Engineers')->find()
				->where(['user_id' => $user_id])
				->contain(['Users', 'Attributes'])
				->first();
		
		$notices = TableRegistry::get('Notices')->find('active',['group_id'=>$group_id]);
		
		$comments = TableRegistry::get('Comments')->find()
				->where(['engineer_id'=>$engineer->id])
				->group('enterprise_id')
				->order(['max(modified)'=>'desc']);
		
		$comments
				->select(['enterprise_id'])
				->select(['engineer_id'])
				->select(['modified' => 'max(modified)'])
				->select(['count'=> $comments->func()->count('*')])
				;

				

		$this->set(compact('engineer','notices','comments'));
		$this->render('engineer');
		return;
	}

}
