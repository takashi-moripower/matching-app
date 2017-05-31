<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
/**
 * Attributes Controller
 *
 * @property \App\Model\Table\AttributesTable $Attributes
 */
class AttributesController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index() {
        $attributes = $this->paginate($this->Attributes);

        $this->set(compact('attributes'));
        $this->set('_serialize', ['attributes']);
    }

    /**
     * View method
     *
     * @param string|null $id Attribute id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $attribute = $this->Attributes->get($id, [
            'contain' => []
        ]);

        $this->set('attribute', $attribute);
        $this->set('_serialize', ['attribute']);
    }

    public function add() {
        $entity = $this->Attributes->newEntity();
        return $this->_edit($entity);
    }

    public function edit($id) {
        $entity = $this->Attributes->get($id);
        return $this->_edit($entity);
    }

    protected function _edit($entity) {
        if ($this->request->is(['post', 'put', 'patch'])) {
            $this->Attributes->patchEntity($entity, $this->request->data);
            $result = $this->Attributes->save($entity);

            if ($result) {
                $this->Flash->success('属性データは正常に保存されました');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->failed('属性データの保存に失敗');
            }
        }

        $this->set('attribute', $entity);
        $this->render('edit');
    }

    /**
     * Delete method
     *
     * @param string|null $id Attribute id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete() {
        $this->request->allowMethod(['post']);
        
        $id = $this->request->data('id');
        
        $table_ae = TableRegistry::get('attributes_engineers');
        $table_ae->query()
                ->delete()
                ->where(['attribute_id'=>$id])
                ->execute();
        
        $table_ao = TableRegistry::get('attributes_offers');
        $table_ao->query()
                ->delete()
                ->where(['attribute_id'=>$id])
                ->execute();
        
        $attribute = $this->Attributes->get($id);
        if ($this->Attributes->delete($attribute)) {
            $this->Flash->success(__('The attribute has been deleted.'));
        } else {
            $this->Flash->error(__('The attribute could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

}
