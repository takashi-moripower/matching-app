<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Defines\Defines;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class DebugController extends AppController {

	public function dummyEngineers() {
		$this->loadComponent('DummyData');
		$this->DummyData->engineers();

		$this->redirect(['controller' => 'home', 'action' => 'index']);
	}

	public function dummyEnterprises() {
		$this->loadComponent('DummyData');
		$this->DummyData->enterprises();

		$this->redirect(['controller' => 'home', 'action' => 'index']);
	}

	public function dummyComments() {
		$this->loadComponent('DummyData');
		$this->DummyData->comments();

		$this->redirect(['controller' => 'home', 'action' => 'index']);
	}

	public function dummyOffers() {
		$this->loadComponent('DummyData');
		$this->DummyData->offers();

		$this->redirect(['controller' => 'home', 'action' => 'index']);
	}

	public function deleteEngineers() {
		$table_u = TableRegistry::get('Users');

		$engineers = $table_u->find()
				->where(['group_id' => Defines::GROUP_ENGINEER]);
		$i = 0;
		foreach ($engineers as $engineer) {
			$table_u->deleteAssociated($engineer);
			$i++;
		}
		$this->set('data', $i);
		$this->render('/Common/debug');
	}

	public function deleteEnterprises() {
		$table_u = TableRegistry::get('Users');

		$engineers = $table_u->find()
				->where(['group_id in' => [Defines::GROUP_ENTERPRISE_FREE, Defines::GROUP_ENTERPRISE_PREMIUM]]);

		$i = 0;
		foreach ($engineers as $engineer) {
			$table_u->deleteAssociated($engineer);
			$i++;
		}
		$this->set('data', $i);
		$this->render('/Common/debug');
	}

	public function deleteContacts() {
		$table_c = TableRegistry::get('Contacts');
		$result = $table_c->connection()->query("TRUNCATE contacts");
		$this->set('data', $result);
		$this->render('/Common/debug');
	}

	public function deleteOffers() {
		$table_o = TableRegistry::get('Offers');
		$table_o->connection()->query("TRUNCATE offers");
		$table_o->connection()->query("TRUNCATE attributes_offers");

		$this->set('data', 0);
		$this->render('/Common/debug');
	}

	public function loginAs($user_id = NULL) {
		$table_u = TableRegistry::get('Users');

		if ($user_id == NULL) {
			$this->paginate = [
				'contain' => ['Groups']
			];
			$users = $this->paginate($table_u);

			$this->set(compact('users'));
			return;
		}

		$user = $table_u->getLoginData($user_id);

		$this->Auth->setUser($user);

		$this->redirect(['controller' => 'home', 'action' => 'index']);
	}

	public function email() {

		$body = "くいっくぶらうんふぉっくすじゃんぷすおーばーれいじいどっぐ";
		$email = new Email();

		$email->template('default')
				->emailFormat('text')
				->to('nin65535@gmail.com')
				->from('takashi@moripower.jp')
				->subject('EMAIL DEBUG')
				->transport('SendGrid');

		if ($email->send($body)) {
			// 成功
		} else {
			// 失敗
		}

		$this->render('/Common/debug');
	}

}
