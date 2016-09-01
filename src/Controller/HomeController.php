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
	
	public function aaa(){
		$this->set('data',$this->Auth->User());
		$this->render('/Common/debug');
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
				$enterprise = TableRegistry::get('Enterprises')->find()
						->where(['user_id' => $user_id])
						->first();
				$this->set(compact('enterprise'));
				$this->render('enterprise');
				return;
				
			case Defines::GROUP_ENGINEER:
				$engineer = TableRegistry::get('Engineers')->find()
						->where(['user_id' => $user_id])
						->first();
				$this->set(compact('engineer'));
				$this->render('engineer');
				return;
		}
	}

	public function debug(){
		$this->request->session()->delete('cakeopauth');
		
		$this->render('/Common/debug');
	}

}
