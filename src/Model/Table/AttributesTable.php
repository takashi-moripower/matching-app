<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Hash;
use App\Defines\Defines;

/**
 * Attributes Model
 *
 * @method \App\Model\Entity\Attribute get($primaryKey, $options = [])
 * @method \App\Model\Entity\Attribute newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Attribute[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Attribute|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Attribute patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Attribute[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Attribute findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AttributesTable extends Table {

	/**
	 * Initialize method
	 *
	 * @param array $config The configuration for the Table.
	 * @return void
	 */
	public function initialize(array $config) {
		parent::initialize($config);

		$this->table('attributes');
		$this->displayField('name');
		$this->primaryKey('id');

		$this->addBehavior('Timestamp');

		$this->belongsToMany('Engineers');
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
				->requirePresence('name', 'create')
				->notEmpty('name');

		$validator
				->integer('type')
				->requirePresence('type', 'create')
				->notEmpty('type');

		$validator
				->allowEmpty('note');

		return $validator;
	}

	public function getTypedList() {

		$result = [];
		foreach (Defines::ATTRIBUTE_TYPE_NAME as $type => $typeName) {
			$result[$type] = $this->find('list', ['keyField' => 'id', 'valueField' => 'name'])
					->where(['type' => $type]);
		}

		return $result;
	}

	public function getTypedListByEngineer($engineer_id) {
		$result = [];
		foreach (Defines::ATTRIBUTE_TYPE_NAME as $type => $typeName) {
			$query = $this->find()
					->innerJoin('attributes_engineers', ['attributes_engineers.attribute_id = Attributes.id' , 'attributes_engineers.engineer_id' => $engineer_id])
					->where(['Attributes.type' => $type])
					->select('Attributes.id');
			
			
			$result[$type] = $query;
		}

		return $result;
	}

}
