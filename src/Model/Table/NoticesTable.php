<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Hash;
use App\Defines\Defines;

/**
 * Notices Model
 *
 * @method \App\Model\Entity\Notice get($primaryKey, $options = [])
 * @method \App\Model\Entity\Notice newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Notice[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Notice|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Notice patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Notice[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Notice findOrCreate($search, callable $callback = null)
 */
class NoticesTable extends Table {

	/**
	 * Initialize method
	 *
	 * @param array $config The configuration for the Table.
	 * @return void
	 */
	public function initialize(array $config) {
		parent::initialize($config);

		$this->table('notices');
		$this->displayField('id');
		$this->primaryKey('id');
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
				->requirePresence('content', 'create')
				->notEmpty('content');

		$validator
				->dateTime('start')
				->allowEmpty('start');

		$validator
				->dateTime('end')
				->allowEmpty('end');

		$validator
				->integer('flags')
				->requirePresence('flags', 'create')
				->notEmpty('flags');

		return $validator;
	}

	public function findActive(Query $query, array $options) {
		$now = new \Cake\I18n\Time;
		$query->where(['or' => [
				'start <' => $now,
				'start is' => NULL
		]]);
		$query->where(['or' => [
				'end >' => $now,
				'end is' => NULL
		]]);

		$group_id = Hash::get($options, 'group_id');

		$flag = Hash::get(Defines::GROUP_NOTICE_FLAG, $group_id);

		if (empty($flag)) {
			return $query;
		}

		return $query->where("flags & {$flag} = {$flag}");
	}

}
