<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Search\Manager;
use App\Defines\Defines;

/**
 * Enterprises Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\HasMany $Contacts
 * @property \Cake\ORM\Association\BelongsToMany $Attributes
 *
 * @method \App\Model\Entity\Enterprise get($primaryKey, $options = [])
 * @method \App\Model\Entity\Enterprise newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Enterprise[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Enterprise|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Enterprise patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Enterprise[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Enterprise findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EnterprisesTable extends Table {

	/**
	 * Initialize method
	 *
	 * @param array $config The configuration for the Table.
	 * @return void
	 */
	public function initialize(array $config) {
		parent::initialize($config);

		$this->table('enterprises');
		$this->displayField('name');
		$this->primaryKey('id');

		$this->addBehavior('Timestamp');
		$this->addBehavior('Search.Search');

		$this->belongsTo('Users', [
			'foreignKey' => 'user_id',
			'joinType' => 'INNER',
			'conditions' => ['Users.expunge is Not' => Defines::USER_EXPUNGE_TRUE],
		]);
	}

	public function associateContact($engineer_id) {
		$this->hasOne('Contacts', [
			'foreignKey' => 'enterprise_id',
			'bindingKey' => 'id',
			'conditions' => [
				'engineer_id' => $engineer_id
			]
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
				->allowEmpty('name');

		$validator
				->allowEmpty('postalcode');

		$validator
				->allowEmpty('address');

		$validator
				->allowEmpty('phone');

		$validator
				->date('establish')
				->allowEmpty('establish');

		$validator
				->integer('capital')
				->allowEmpty('capital');

		$validator
				->integer('employee')
				->allowEmpty('employee');

		$validator
				->dateTime('crated')
				->allowEmpty('crated');

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

	/**
	 * 検索コンポーネント用
	 * @return Manager
	 */
	public function searchConfiguration() {
		$search = new Manager($this);

		return $search;
	}

}
