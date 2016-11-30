<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Hash;
use App\Defines\Defines;
use Search\Manager;

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
		$this->addBehavior('Search.Search');

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

	public function findCollection(Query $query, $options) {

		if (Hash::get($options, 'name', true)) {
			$query
					->find('EngineerName')
					->find('EnterpriseName');
		}


		$c2 = $this->find()
				->from(['c2' => 'comments'])
				->where('c2.engineer_id = Comments.engineer_id')
				->where('c2.enterprise_id = Comments.enterprise_id ')
		;

		$c3 = $c2->cleanCopy();


		$query
				->where(['Comments.modified' => $c2->select(['last_modified' => 'max(c2.modified)'])])
				->select(['count' => $c3->select(['count' => 'count(*)'])])
				->select($this)
				->select(['read_engineer' => 'flags & ' . Defines::COMMENT_FLAG_READ_ENGINEER])
				->select(['read_enterprise' => 'flags & ' . Defines::COMMENT_FLAG_READ_ENTERPRISE])
				->select(['direction' => 'flags & ' . Defines::COMMENT_FLAG_SEND_MASK])
		;

		return $query;
	}

	/**
	 * engineer_nameを取得
	 * @param Query $query
	 * @param type $options
	 * @return Query
	 */
	public function findEngineerName(Query $query, $options) {
		$query
				->join([
					'Engineers' => [
						'table' => 'engineers',
						'type' => 'left',
						'conditions' => 'Engineers.id = Comments.engineer_id'
					],
					'EngineerUser' => [
						'table' => 'users',
						'type' => 'left',
						'conditions' => 'EngineerUser.id = Engineers.user_id'
					]
				])
				->select(['engineer_name' => 'EngineerUser.name']);

		return $query;
	}

	/**
	 * enterprise_nameを取得
	 * @param Query $query
	 * @param type $options
	 * @return Query
	 */
	public function findEnterpriseName(Query $query, $options) {
		$query
				->join([
					'Enterprises' => [
						'table' => 'enterprises',
						'type' => 'left',
						'conditions' => 'Enterprises.id = Comments.enterprise_id'
					],
					'EnterpriseUser' => [
						'table' => 'users',
						'type' => 'left',
						'conditions' => 'EnterpriseUser.id = Enterprises.user_id'
					]
				])
				->select(['enterprise_name' => 'EnterpriseUser.name']);

		return $query;
	}

	/**
	 * 検索コンポーネント用
	 * @return Manager
	 */
	public function searchConfiguration() {
		$search = new Manager($this);
		$search
				->like('freeword', ['before' => true, 'after' => true, 'field' => [$this->aliasField('content'),  'EngineerUser.name' , 'EnterpriseUser.name']])
		;
		return $search;
	}

}
