<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Defines\Defines;

/**
 * Informations Controller
 *
 * @property \App\Model\Table\InformationsTable $Informations
 */
class InformationsController extends AppController {

	/**
	 * Index method
	 *
	 * @return \Cake\Network\Response|null
	 */
	public function index() {
		$this->paginate = [
			'contain' => ['Users', 'Files'],
			'order' => ['user_id' => 'ASC', 'published' => 'ASC']
		];
		$informations = $this->paginate(
				$this->Informations->find()
						->where(['user_id' => $user_id])
		);

		$this->set(compact('informations'));
		$this->set('_serialize', ['informations']);
	}

	public function managementSelf() {
		return $this->management($this->_getLoginUser('id'));
	}

	public function management($user_id) {
		$user = $this->Informations->Users->get($user_id);
		$informations = $this->Informations->find()
				->where(['user_id' => $user_id])
				->order(['order_num'=>'ASC'])
				->contain(['Files']);

		$new_information = $this->Informations->newEntity([
			'user_id' => $user_id,
			'order_num' => $this->Informations->find()
					->where(['user_id' => $user_id])
					->count(),
			'published' => Defines::INFORMATION_PUBLISH_TRUE,
			'files'=> [],
		]);

		$this->set(compact('informations', 'user', 'new_information'));
		$this->render('management');
	}

	/**
	 * View method
	 *
	 * @param string|null $id Information id.
	 * @return \Cake\Network\Response|null
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function view($id = null) {
		$information = $this->Informations->get($id, [
			'contain' => ['Users', 'Files']
		]);

		$this->set('information', $information);
		$this->set('_serialize', ['information']);
	}

	public function add() {
		$entity = $this->Informations->newEntity();
		return $this->_edit($entity);
	}

	public function edit($id) {
		$entity = $this->Informations->get($id, ['contain' => ['Files']]);
		return $this->_edit($entity);
	}

	protected function _edit($entity) {
		if ($this->request->is(['post', 'put', 'patch'])) {

			if (isset($this->request->data['files'])) {

				$files = $this->request->data['files'];
				foreach ($files as $pos => $file) {
					if ($file['error']) {
						unset($this->request->data['files'][$pos]);
					}
				}
			}

			$this->Informations->patchEntity($entity, $this->request->data);

			if ($this->Informations->save($entity)) {
				$this->Informations->setOrder( $entity->user_id );
				$this->Flash->success('追加情報データが正常に保存されました');
			} else {
				$this->Flash->success('追加情報データの保存に失敗しました');
			}

			$this->redirect(['controller' => 'informations', 'action' => 'management', $entity->user_id]);
		}
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id Information id.
	 * @return \Cake\Network\Response|null Redirects to index.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function delete($id = null) {
		$this->request->allowMethod(['post', 'delete']);
		$information = $this->Informations->get($id);
		if ($this->Informations->delete($information)) {
			$this->Flash->success(__('The information has been deleted.'));
		} else {
			$this->Flash->error(__('The information could not be deleted. Please, try again.'));
		}

		return $this->redirect(['action' => 'index']);
	}
	
	public function moveUp( $user_id , $information_id ){
		$this->Informations->move( $user_id , $information_id , -1 );
		$this->redirect(['controller'=>'informations','action'=>'management',$user_id]);
	}
	public function moveDown( $user_id , $information_id ){
		$this->Informations->move( $user_id , $information_id , 1 );
		$this->redirect(['controller'=>'informations','action'=>'management',$user_id]);
	}

}
