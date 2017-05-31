<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Defines\Defines;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

/**
 * Enterprises Controller
 *
 * @property \App\Model\Table\EnterprisesTable $Enterprises
 */
class EnterprisesController extends AppController {

    public $paginate = [
        'sortWhitelist' => [
            'id',
            'Users.name',
            'Users.group_id',
            'establish',
            'capital',
            'employee',
        ],
        'order' => [
            'id' => 'asc',
        ]
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
        /* 	各種パラメータによる絞り込み	 */
        $search_default = [
        ];

        $search_param = $this->request->data + $search_default;
        $query = $this->Enterprises->find('search', $this->Enterprises->filterParams($search_param));

        /* 	取得するフィールドの定義		 */
        $contain = [
            'Users' => ['fields' => ['name', 'group_id']],
            'Users.Groups' => ['fields' => ['name']]
        ];
        $query->contain($contain);

        $enterprises = $this->paginate($query);

        $this->set(compact('enterprises'));
        $this->set('_serialize', ['enterprises']);
    }

    /**
     * View method
     *
     * @param string|null $id Enterprise id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id) {
        $enterprise = $this->Enterprises->get($id, [
            'contain' => ['Users' => ['Informations' => ['Files']]]
        ]);
        $loginUser = $this->_getLoginUser();
        if ($loginUser['group_id'] == Defines::GROUP_ENGINEER) {
            TableRegistry::get('Contacts')->setEngineerAccess($loginUser['engineer_id'], $id, Defines::CONTACT_RECORD_VIEW);
        }

        $this->set('enterprise', $enterprise);
        $this->set('_serialize', ['enterprise']);
    }

    public function addressSelf() {
        $user_id = $this->_getLoginUser('id');
        return $this->address($user_id);
    }

    public function address($user_id) {
        $enterprise = $this->Enterprises->find()
                ->where(['user_id' => $user_id])
                ->contain(['Users'])
                ->first();

        return $this->_edit($enterprise, ['template' => 'address']);
    }

    protected function _edit($enterprise, $options) {
        if ($this->request->is(['post', 'put', 'patch'])) {

            $this->Enterprises->patchEntity($enterprise, $this->request->data);

            $result = $this->Enterprises->save($enterprise);

            if ($result) {
                $this->Flash->success('企業データは正常に保存されました');
                $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->success('企業データの保存に失敗');
            }
        }
        $this->set('enterprise', $enterprise);

        if (isset($options['template'])) {
            $this->render($options['template']);
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Enterprise id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $enterprise = $this->Enterprises->get($id);
        if ($this->Enterprises->delete($enterprise)) {
            $this->Flash->success(__('The enterprise has been deleted.'));
        } else {
            $this->Flash->error(__('The enterprise could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function offers($id) {
        $request = ['enterprise' => $id];

        $this->request->session()->write('search.offers.index', $request);
        $this->redirect(['controller' => 'offers', 'action' => 'index']);
    }

}
