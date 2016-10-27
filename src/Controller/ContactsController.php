<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Defines\Defines;

/**
 * Contacts Controller
 *
 * @property \App\Model\Table\ContactsTable $Contacts
 */
class ContactsController extends AppController {

	/**
	 * Index method
	 *
	 * @return \Cake\Network\Response|null
	 */
	public function index() {
		$this->paginate = [
			'contain' => ['Engineers' => ['Users'], 'Enterprises' => ['Users']]
		];
		$contacts = $this->paginate($this->Contacts);

		$this->set(compact('contacts'));
		$this->set('_serialize', ['contacts']);
	}

	/**
	 * View method
	 *
	 * @param string|null $id Contact id.
	 * @return \Cake\Network\Response|null
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function view($engineer_id, $enterprise_id) {
		$contact = $this->Contacts->find()
				->where(['engineer_id' => $engineer_id, 'enterprise_id' => $enterprise_id])
				->contain([
					'Engineers' => ['Users'],
					'Enterprises' => ['Users']
				])
				->first();

		$this->set(['contact' => $contact]);
	}

	public function rankEngineer() {

		$f = Defines::CONTACT_RECORD_COMMENT;

		$contacts = $this->Contacts->find()
				->contain(['Engineers' => [ 'Users']])
				->select(['comment' => "sum( CASE WHEN enterprise_record & " . Defines::CONTACT_RECORD_COMMENT . " THEN 1 ELSE 0 END )"])
				->select(['search' => "sum( CASE WHEN enterprise_record & " . Defines::CONTACT_RECORD_SEARCH . " THEN 1 ELSE 0 END )"])
				->select(['count' => "sum( enterprise_count )"])
				->select('engineer_id')
				->select('Engineers.id')
				->select('Engineers.user_id')
				->select('Users.id')
				->select(['user_name' => 'Users.name'])
				->group('engineer_id')


		;

		$this->set('contacts', $contacts);
	}

}
