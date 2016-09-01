<?php

namespace App\Controller;

use App\Controller\AppController;

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
			'contain' => ['Engineers', 'Enterprises']
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
					'Engineers'=>['Users'],
					'Enterprises'=>['Users']
					])
				->first();
		
		$this->set(['contact'=>$contact]);
		
	}

	/**
	 * Add method
	 *
	 * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
	 */
	public function add() {
		$contact = $this->Contacts->newEntity();
		if ($this->request->is('post')) {
			$contact = $this->Contacts->patchEntity($contact, $this->request->data);
			if ($this->Contacts->save($contact)) {
				$this->Flash->success(__('The contact has been saved.'));

				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('The contact could not be saved. Please, try again.'));
			}
		}
		$engineers = $this->Contacts->Engineers->find('list', ['limit' => 200]);
		$enterprises = $this->Contacts->Enterprises->find('list', ['limit' => 200]);
		$this->set(compact('contact', 'engineers', 'enterprises'));
		$this->set('_serialize', ['contact']);
	}

	/**
	 * Edit method
	 *
	 * @param string|null $id Contact id.
	 * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function edit($id = null) {
		$contact = $this->Contacts->get($id, [
			'contain' => []
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$contact = $this->Contacts->patchEntity($contact, $this->request->data);
			if ($this->Contacts->save($contact)) {
				$this->Flash->success(__('The contact has been saved.'));

				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('The contact could not be saved. Please, try again.'));
			}
		}
		$engineers = $this->Contacts->Engineers->find('list', ['limit' => 200]);
		$enterprises = $this->Contacts->Enterprises->find('list', ['limit' => 200]);
		$this->set(compact('contact', 'engineers', 'enterprises'));
		$this->set('_serialize', ['contact']);
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id Contact id.
	 * @return \Cake\Network\Response|null Redirects to index.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function delete($id = null) {
		$this->request->allowMethod(['post', 'delete']);
		$contact = $this->Contacts->get($id);
		if ($this->Contacts->delete($contact)) {
			$this->Flash->success(__('The contact has been deleted.'));
		} else {
			$this->Flash->error(__('The contact could not be deleted. Please, try again.'));
		}

		return $this->redirect(['action' => 'index']);
	}

}
