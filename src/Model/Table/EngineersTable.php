<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\I18n\Date;
use Cake\Utility\Hash;
use App\Defines\Defines;
use Search\Manager;

/**
 * Engineers Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\HasMany $AttributesEnterprises
 * @property \Cake\ORM\Association\HasMany $Comments
 * @property \Cake\ORM\Association\HasMany $Contacts
 *
 * @method \App\Model\Entity\Engineer get($primaryKey, $options = [])
 * @method \App\Model\Entity\Engineer newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Engineer[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Engineer|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Engineer patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Engineer[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Engineer findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EngineersTable extends Table {

	/**
	 * Initialize method
	 *
	 * @param array $config The configuration for the Table.
	 * @return void
	 */
	public function initialize(array $config) {
		parent::initialize($config);

		$this->table('engineers');
		$this->displayField('name');
		$this->primaryKey('id');

		$this->addBehavior('Timestamp');
		$this->addBehavior('Search.Search');

		$this->belongsTo('Users', [
			'foreignKey' => 'user_id',
			'joinType' => 'INNER',
			'conditions' => ['Users.expunge is Not' => Defines::USER_EXPUNGE_TRUE],
		]);

		$this->belongsToMany('Attributes', [
			'sort' => ['Attributes.type' => 'ASC', 'Attributes.id' => 'ASC']
		]);
	}

	public function associateContact($enterprise_id) {

		$this->hasOne('Contacts', [
			'foreignKey' => 'engineer_id',
			'bindingKey' => 'id',
			'conditions' => [
				'enterprise_id' => $enterprise_id
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
				->date('birthday')
				->allowEmpty('birthday');

		$validator
				->allowEmpty('education');

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
		$search
				->finder('attributes', [ 'finder' => 'Attributes'])
				->finder('age_min', ['finder' => 'ageMin'])
				->finder('age_max', ['finder' => 'ageMax'])
		;
		return $search;
	}

	public function findAgeMin(Query $query, array $options) {
		$age_min = $options['age_min'];
		$date_max = new Date("-{$age_min}years");

		$query->where(['birthday <=' => $date_max]);

		return $query;
	}

	public function findAgeMax(Query $query, array $options) {
		$age_max = $options['age_max'] + 1;
		$date_min = new Date("-{$age_max}years");

		$query->where(['birthday >' => $date_min]);

		return $query;
	}

	public function findAttributes(Query $query, array $options) {
		$types = $options['attributes'];

		foreach ($types as $type) {
			if (!empty($type['values'])) {
				$query->find('AttributeType', ['operation' => $type['operation'], 'values' => $type['values']]);
			}
		}

		return $query;
	}

	public function findAttributeType(Query $query, array $options) {
		$values = Hash::get($options, 'values', []);
		$operation = Hash::get($options, 'operation', Defines::OFFER_OPERATION_AND);

		$subqueries = [];

		foreach ($values as $attribute_id) {
			$subquery = $query->cleanCopy();
			$subquery->find('Attribute', ['attribute_id' => $attribute_id]);
			$subqueries[] = $subquery;
		}

		if ($operation == Defines::OFFER_OPERATION_AND) {
			foreach ($subqueries as $sq) {
				$query->andWhere(['Engineers.id in' => $sq->select('Engineers.id')]);
			}
		} else {
			$or_state = [];
			foreach ($subqueries as $sq) {
				$or_state[] = [
					'Engineers.id in' => $sq->select('Engineers.id')
				];
			}

			$query->where([ 'or' => $or_state]);
		}


		return $query;
	}

	public function findAttribute(Query $query, array $options) {
		$attribute_id = $options['attribute_id'];

		$query->innerJoin('attributes_engineers', 'Engineers.id = attributes_engineers.engineer_id')
				->where(['attributes_engineers.attribute_id' => $attribute_id]);

		return $query;
	}

}
