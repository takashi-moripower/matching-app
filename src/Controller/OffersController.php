<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Defines\Defines;
use Cake\Utility\Hash;
use Cake\ORM\TableRegistry;

/**
 * Offers Controller
 *
 * @property \App\Model\Table\OffersTable $Offers
 */
class OffersController extends AppController {

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

		// 	パラメータによる絞り込み
		$search_default = [
		];

		$search_param = $this->request->data + $search_default;
		$query = $this->Offers->find('search', $this->Offers->filterParams($search_param));
		
		/*  表示件数設定　 */
		if (isset($search_param['limit'])) {
			$this->paginate['limit'] = $search['limit'];
		}

		// 	取得するフィールドの定義	
		$contain = [
			'Enterprises'=>['Users'],
			'Attributes'
		];
		$query->contain($contain);
		
		//	データ取得
		$offers = $this->paginate($query);
		
		//	search form用データ
		$attributes = $this->Offers->Attributes->getTypedList();
		$enterprises_data = $this->Offers->Enterprises
				->find()
				->contain('Users');
		
		$enterprises = [];
		
		foreach( $enterprises_data as $e ){
			$enterprises[ $e->id ] = $e->user->name;
		}

		$this->set('search',$search_param);
		$this->set(compact('offers', 'attributes','enterprises'));
		$this->set('_serialize', ['engineers']);
	}

	/**
	 * View method
	 *
	 * @param string|null $id Offer id.
	 * @return \Cake\Network\Response|null
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function view($id = null) {
		$offer = $this->Offers->get($id, [
			'contain' => ['Enterprises' => ['Users'], 'Attributes']
		]);

		$this->set('offer', $offer);
		$this->set('_serialize', ['offer']);
	}

	/**
	 * Add method
	 *
	 * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
	 */
	public function add() {
		$entity = $this->Offers->newEntity([
			'enterprise_id' => $this->_getLoginUser('enterprise_id'),
			'attributes' => []
		]);

		return $this->_edit($entity);
	}

	public function edit($id) {
		$entity = $this->Offers->get($id, ['contain' => ['Attributes']]);
		return $this->_edit($entity);
	}

	protected function _edit($entity) {
		if ($this->request->is(['post', 'put', 'patch'])) {

			$this->Offers->patchEntity($entity, $this->request->data);
			$this->Offers->save($entity);
			return $this->redirect(['controller' => 'offers', 'action' => 'index']);
		}

		$this->set('offer', $entity);
		$attributes = $this->Offers->Attributes->getTypedList();
		$this->set(['attributes' => $attributes]);

		$this->render('edit');
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id Offer id.
	 * @return \Cake\Network\Response|null Redirects to index.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function delete($id = null) {
		$this->request->allowMethod(['post', 'delete']);
		$offer = $this->Offers->get($id);
		if ($this->Offers->delete($offer)) {
			$this->Flash->success(__('The offer has been deleted.'));
		} else {
			$this->Flash->error(__('The offer could not be deleted. Please, try again.'));
		}

		return $this->redirect(['action' => 'index']);
	}

	public function search($id) {
		$offer = $this->Offers->get($id, ['contain' => ['Attributes']]);

		$request = $offer->getSearchRequest();
		$this->request->session()->write('search.engineers.index', $request);

		$this->redirect(['controller' => 'engineers', 'action' => 'index']);
	}

	public function match($engineer_id = NULL) {
		if (empty($engineer_id)) {
			$engineer_id = $this->_getLoginUser('engineer_id');
		}
		
		$engineer = TableRegistry::get('Engineers')->get( $engineer_id , ['contain'=>['Attributes']]);
		$request = $engineer->getSearchRequest();
		$this->request->session()->write('search.offers.index',$request);
		
		$this->redirect(['controller'=>'offers','action'=>'index']);
	}

}
