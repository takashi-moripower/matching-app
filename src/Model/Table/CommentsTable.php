<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Comments Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Engineers
 * @property \Cake\ORM\Association\BelongsTo $Enterprises
 *
 * @method \App\Model\Entity\Comment get($primaryKey, $options = [])
 * @method \App\Model\Entity\Comment newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Comment[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Comment|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Comment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Comment[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Comment findOrCreate($search, callable $callback = null)
 */
class CommentsTable extends Table {

	/**
	 * Initialize method
	 *
	 * @param array $config The configuration for the Table.
	 * @return void
	 */
	public function initialize(array $config) {
		parent::initialize($config);

		$this->table('comments');
		$this->displayField('id');
		$this->primaryKey('id');
		
		$this->addBehavior('Timestamp');

		$this->belongsTo('Engineers', [
			'foreignKey' => 'engineer_id',
			'joinType' => 'INNER'
		]);
		$this->belongsTo('Enterprises', [
			'foreignKey' => 'enterprise_id',
			'joinType' => 'INNER'
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
				->notEmpty('content');

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
		$rules->add($rules->existsIn(['engineer_id'], 'Engineers'));
		$rules->add($rules->existsIn(['enterprise_id'], 'Enterprises'));

		return $rules;
	}
	
	public function findCollection( Query $query , $options ){

		$query
				->select(['enterprise_id'])
				->select(['engineer_id'])
				->select(['last_modified' => 'max(modified)'])
				->select(['count'=> $query->func()->count('*')])
				->order(['last_modified'=>'desc']);
		
		return $query;
	}

}
