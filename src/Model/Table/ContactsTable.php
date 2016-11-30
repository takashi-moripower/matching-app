<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use App\Defines\Defines;

/**
 * Contacts Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Engineers
 * @property \Cake\ORM\Association\BelongsTo $Enterprises
 *
 * @method \App\Model\Entity\Contact get($primaryKey, $options = [])
 * @method \App\Model\Entity\Contact newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Contact[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Contact|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Contact patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Contact[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Contact findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ContactsTable extends Table {

	/**
	 * Initialize method
	 *
	 * @param array $config The configuration for the Table.
	 * @return void
	 */
	public function initialize(array $config) {
		parent::initialize($config);

		$this->table('contacts');
		$this->displayField('id');
		$this->primaryKey('id');

		$this->addBehavior('Timestamp');
		$this->addBehavior('LoginUser');

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
				->integer('engineer_access')
				->allowEmpty('engineer_access');

		$validator
				->dateTime('engineer_viewdate')
				->allowEmpty('engineer_viewdate');

		$validator
				->integer('engineer_interest')
				->allowEmpty('engineer_interest');

		$validator
				->integer('enterprise_access')
				->allowEmpty('enterprise_access');

		$validator
				->dateTime('enterprise_viewdate')
				->allowEmpty('enterprise_viewdate');

		$validator
				->allowEmpty('enterprise_interest');

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

	public function getByEE($engineer_id, $enterprise_id) {
		$entity = $this->find()
				->where(['engineer_id' => $engineer_id, 'enterprise_id' => $enterprise_id])
				->first();

		if (empty($entity)) {
			$entity = $this->newEntity(['engineer_id' => $engineer_id, 'enterprise_id' => $enterprise_id]);
			$r = $this->save($entity);
		}

		return $entity;
	}

	/**
	 * 企業からのアクセス
	 * @param type $engineer_id
	 * @param type $enterprise_id
	 * @param type $flag
	 * @return type
	 */
	public function setEnterpriseAccess( $engineer_id , $enterprise_id , $flag ){
		$entity = $this->getByEE( $engineer_id , $enterprise_id );
		
		$entity->enterprise_record |= $flag;
		$entity->enterprise_date = \Cake\I18n\Time::now();
		if( isset( $entity->enterprise_count) ){
			$entity->enterprise_count ++;
		}else{
			$entity->enterprise_count = 1;
		}
		$this->save( $entity );

		return $entity;
	}

	/**
	 * 技術者からのアクセス
	 * @param type $engineer_id
	 * @param type $enterprise_id
	 * @param type $flag
	 * @return type
	 */
	public function setEngineerAccess( $engineer_id , $enterprise_id , $flag ){
		$entity = $this->getByEE( $engineer_id , $enterprise_id );

		$entity->engineer_record |= $flag;
		$entity->engineer_date = \Cake\I18n\Time::now();

		
		if( isset( $entity->engineer_count) ){
			$entity->engineer_count ++;
		}else{
			$entity->engineer_count = 1;
		}
		$this->save( $entity );
		
		return $entity;
	}
	
	public function getBorder( $rank = 10 ){
		$result = $this->find()
				->select(['count' => "sum( enterprise_count )"])
				->group( 'engineer_id' )
				->order(['count' => 'desc'])
				->limit(1)
				->offset($rank)
				->first();
		
		if( $result ){
			return $result->count;
		}
		return 0;
	}
}
