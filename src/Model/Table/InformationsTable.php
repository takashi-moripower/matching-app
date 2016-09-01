<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;

/**
 * Informations Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\HasMany $Files
 *
 * @method \App\Model\Entity\Information get($primaryKey, $options = [])
 * @method \App\Model\Entity\Information newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Information[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Information|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Information patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Information[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Information findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class InformationsTable extends Table {

	/**
	 * Initialize method
	 *
	 * @param array $config The configuration for the Table.
	 * @return void
	 */
	public function initialize(array $config) {
		parent::initialize($config);

		$this->table('informations');
		$this->displayField('title');
		$this->primaryKey('id');

		$this->addBehavior('Timestamp');

		$this->belongsTo('Users', [
			'foreignKey' => 'user_id',
			'joinType' => 'INNER'
		]);
		$this->hasMany('Files', [
			'foreignKey' => 'information_id'
		]);
	}

	/**
	 * Default validation rules.
	 *
	 * @param \Cake\Validation\Validator $validator Validator instance.
	 * @return \Cake\Validation\Validator
	 */
	public function validationDefault(Validator $validator) {
		$validator
				->integer('id')
				->allowEmpty('id', 'create');

		$validator
				->integer('order_num')
				->allowEmpty('order_num');

		$validator
				->notEmpty('title');

		$validator
				->notEmpty('content');

		$validator
				->integer('published')
				->allowEmpty('published');

		return $validator;
	}

	/**
	 * Returns a rules checker object that will be used for validating
	 * application integrity.
	 *
	 * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
	 * @return \Cake\ORM\RulesChecker
	 */
	public function buildRules(RulesChecker $rules) {
		$rules->add($rules->existsIn(['user_id'], 'Users'));

		return $rules;
	}

	public function afterSave(Event $event, EntityInterface $entity, \ArrayObject $options) {
		$table_f = TableRegistry::get('Files');

		if (!empty($entity->file_remove)) {
			foreach ($entity->file_remove as $file_id => $value) {
				if ($value) {
					$file = $table_f->get($file_id);
					$table_f->delete($file);
				}
			}
		}
	}

	public function setOrder($user_id) {
		$informations = $this->find()
				->where(['user_id' => $user_id])
				->order(['order_num' => 'ASC']);

		$o = 0;
		foreach ($informations as $information) {
			$information->order_num = $o;
			$this->save($information);
			$o++;
		}
	}

	public function move( $user_id , $information_id, $direction) {
		$this->setOrder($user_id);

		$information = $this->get($information_id);

		$old_order = $information->order_num;
		$new_order = $information->order_num + $direction;
		

		$information2 = $this->find()
				->where(['order_num' => $new_order])
				->first();
		
		$information->order_num = $new_order;
		$this->save($information);
		if (!empty($information2)) {
			$information2->order_num = $old_order;
			$this->save($information2);
		}
		
		$this->setOrder($user_id);
	}

}
