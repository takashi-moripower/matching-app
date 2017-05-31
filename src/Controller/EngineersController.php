<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Defines\Defines;
use Cake\ORM\TableRegistry;

/**
 * Engineers Controller
 *
 * @property \App\Model\Table\EngineersTable $Engineers
 */
class EngineersController extends AppController {

    public $paginate = [
        'sortWhitelist' => [
            'Users.name',
            'birthday',
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
        $query = $this->Engineers->find('search', $this->Engineers->filterParams($search_param));
        /*  表示件数設定　 */
        if (isset($search_param['limit'])) {
            $this->paginate['limit'] = $search_param['limit'];
        }

        /* 	取得するフィールドの定義		 */
        $contain = [
            'Users' => ['fields' => ['name']],
            'Attributes'
        ];

        $query->contain($contain);

        $engineers = $this->paginate($query);
        $attributes = $this->Engineers->Attributes->getTypedList();

        $this->set('search', $search_param);
        $this->set(compact('engineers', 'attributes'));
        $this->set('_serialize', ['engineers']);
    }

    /**
     * View method
     *
     * @param string|null $id Engineer id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id) {
        $engineer = $this->Engineers->get($id, [
            'contain' => ['Users' => ['Informations' => ['Files']], 'Attributes']
        ]);

        $loginUser = $this->_getLoginUser();
        if (Defines::isEnterprise($loginUser['group_id'])) {
            $table_c = TableRegistry::get('Contacts');
            $table_c->setEnterpriseAccess($id, $loginUser['enterprise_id'], Defines::CONTACT_RECORD_VIEW);
        }

        $this->set('engineer', $engineer);
        $this->set('_serialize', ['engineer']);
    }

    /**
     * 連絡先編集　一般技術者用
     * @return type
     */
    public function addressSelf() {
        return $this->address($this->_getLoginUser('id'));
    }

    /**
     * 連絡先編集　管理者用
     * @param type $user_id
     */
    public function address($user_id) {
        $engineer = $this->Engineers->find()
                ->where(['user_id' => $user_id])
                ->contain(['Users'])
                ->first();

        $this->_edit($engineer, ['template' => 'address']);
    }

    public function attributesSelf() {
        return $this->attributes($this->_getLoginUser('id'));
    }

    public function attributes($user_id) {
        $engineer = $this->Engineers->find()
                ->where(['user_id' => $user_id])
                ->contain(['Users', 'Attributes'])
                ->first();

        $attributes = $this->Engineers->Attributes->getTypedList();
        $this->set(['attributes' => $attributes]);

        $this->_edit($engineer, ['template' => 'attributes']);
    }

    protected function _edit($engineer, $options) {
        if ($this->request->is(['post', 'put', 'patch'])) {

            $this->Engineers->patchEntity($engineer, $this->request->data);

            $result = $this->Engineers->save($engineer);

            if ($result) {
                $this->Flash->success('技術者データは正常に保存されました');
            } else {
                $this->Flash->success('技術者データの保存に失敗');
            }
        }
        $this->set('engineer', $engineer);
        if (isset($options['template'])) {
            return $this->render($options['template']);
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Engineer id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $engineer = $this->Engineers->get($id);
        if ($this->Engineers->delete($engineer)) {
            $this->Flash->success(__('The engineer has been deleted.'));
        } else {
            $this->Flash->error(__('The engineer could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * 編集　属性
     * @param type $id
     * @return type
     */
/*    public function setAttribute($id) {
        $attributes = $this->Engineers->Attributes->getTypedList();
        $this->set('attributes', $attributes);

        $engineer = $this->Engineers->get($id, ['contain' => ['Users', 'Attributes']]);
        return $this->_editEngineer($engineer, 'set_attribute');
    }

    public function setSpec($id) {
        $engineer = $this->Engineers->get($id, ['contain' => ['Users', 'Attributes']]);
        return $this->_editEngineer($engineer, 'set_spec');
    }

    public function debug() {
        $table_o = TableRegistry::get('Offers');

        $list = $table_o->find('MatchTypeOr', [
                    'type' => Defines::ATTRIBUTE_TYPE_LANGUAGE,
                    'attributes' => [1, 2, 3]
                ])->find('list')->toArray();

        $this->set('data', $list);
        $this->render('/Common/debug');
    }
*/
}
