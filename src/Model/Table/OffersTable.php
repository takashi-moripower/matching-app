<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Utility\Hash;
use App\Defines\Defines;
use Search\Manager;

/**
 * Offers Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Enterprises
 * @property \Cake\ORM\Association\BelongsToMany $Attributes
 *
 * @method \App\Model\Entity\Offer get($primaryKey, $options = [])
 * @method \App\Model\Entity\Offer newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Offer[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Offer|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Offer patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Offer[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Offer findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OffersTable extends Table {

	/**
	 * Initialize method
	 *
	 * @param array $config The configuration for the Table.
	 * @return void
	 */
	public function initialize(array $config) {
		parent::initialize($config);

		$this->table('offers');
		$this->displayField('title');
		$this->primaryKey('id');

		$this->addBehavior('Timestamp');
		$this->addBehavior('Search.Search');

		$this->belongsTo('Enterprises', [
			'foreignKey' => 'enterprise_id',
			'joinType' => 'INNER'
		]);
		$this->belongsToMany('Attributes', [
			'foreignKey' => 'offer_id',
			'targetForeignKey' => 'attribute_id',
			'joinTable' => 'attributes_offers'
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
				->notEmpty('title');

		$validator
				->allowEmpty('note');

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
		$rules->add($rules->existsIn(['enterprise_id'], 'Enterprises'));

		return $rules;
	}

	/**
	 * 検索コンポーネント用
	 * @return Manager
	 */
	public function searchConfiguration() {
		$search = new Manager($this);
		$search
				->value('enterprise',['field' => $this->aliasField('enterprise_id')])
				->finder('attributes', [ 'finder' => 'Match']);
		
		return $search;
	}

	public function findMatch(Query $query, array $options) {

		if (empty($options['attributes'])) {
			$engineer_id = $options['engineer_id'];
			$attributes = TableRegistry::get('Attributes')->getTypedListByEngineer($engineer_id);
		} else {
			$attributes = $options['attributes'];
		}

		foreach (Defines::ATTRIBUTE_TYPE_NAME as $type_id => $type_name) {
			$query->find('MatchType', ['type' => $type_id, 'attributes' => $attributes]);
		}

		return $query;
	}

	public function findMatchType(Query $query, array $options) {
		$type = Hash::get($options, 'type');
		$attributes = Hash::get($options, "attributes.{$type}", []);

		if (!empty($attributes)) {
			//TYPE内条件がANDかつ条件適合するofferを取得
			$subquery_and = $query->cleanCopy();
			$subquery_and->find('MatchTypeAnd', ['attributes' => $attributes, 'type' => $type]);
			$id_and = array_keys($subquery_and->find('list')->toArray());

			//TYPE内条件がORかつ条件適合するofferを取得
			$subquery_or = $query->cleanCopy();
			$subquery_or->find('MatchTypeOr', ['attributes' => $attributes, 'type' => $type]);
			$id_or = array_keys($subquery_or->find('list')->toArray());
			
		} else {
			$id_and = [];
			$id_or = [];
		}

		//TYPE内条件が存在しないofferを取得
		$subquery_void = $query->cleanCopy();
		$subquery_void->find('MatchTypeVoid', ['type' => $type]);
		$id_void = array_keys($subquery_void->find('list')->toArray());


		$id = array_merge($id_and, $id_or, $id_void);

		if (!empty($id)) {
			$query->where(['Offers.id in' => $id]);
		}

		return $query;
	}

	//TYPE内条件がANDかつ条件適合するofferを取得
	public function findMatchTypeAnd(Query $query, array $options) {
		$type = Hash::get($options, 'type');
		$attributes = Hash::get($options, 'attributes');

		$operation_key = "operation{$type}";

		return $query->where([$operation_key => Defines::OFFER_OPERATION_AND])
						->innerJoin(['AO1' => 'attributes_offers'], ['AO1.offer_id = Offers.id'])  //	
						->innerJoin(['A1' => 'attributes'], ['A1.id = AO1.attribute_id', 'A1.type' => $type])
						->leftJoin(['AO2' => 'attributes_offers'], ['AO2.offer_id = Offers.id', 'AO2.attribute_id in' => $attributes, 'AO2.id = AO1.id'])
						->group(['Offers.id'])
						->select(['Offers.id',])
						->select(['count1' => $query->func()->count('AO1.attribute_id')])
						->select(['count2' => $query->func()->count('AO2.attribute_id')])
						->having(['count1 = count2'])
		;
	}

	//TYPE内条件がORかつ条件適合するofferを取得
	public function findMatchTypeOr(Query $query, array $options) {
		$type = Hash::get($options, 'type');
		$attributes = Hash::get($options, 'attributes');

		$operation_key = "operation{$type}";


		return $query->where([$operation_key => Defines::OFFER_OPERATION_OR])
						->innerJoin(['AO1' => 'attributes_offers'], ['AO1.offer_id = Offers.id', 'AO1.attribute_id in' => $attributes,])  //	
						->innerJoin(['A1' => 'attributes'], ['A1.id = AO1.attribute_id', 'A1.type' => $type])
						->group(['Offers.id'])
						->select(['Offers.id',])
						->select(['count1' => $query->func()->count('AO1.attribute_id')])
						->having(['count1 > 0'])
		;
	}

	//TYPE内条件が存在しないofferを取得
	public function findMatchTypeVoid(Query $query, array $options) {
		$type = Hash::get($options, 'type');

		$query
				->innerJoin(['AO1' => 'attributes_offers'], ['AO1.offer_id = Offers.id',])
				->leftJoin(['A1' => 'attributes'], ['A1.id = AO1.attribute_id', 'A1.type' => $type])
				->select('Offers.id')
				->select(['count1' => $query->func()->count('A1.id')])
				->group('Offers.id')
				->having(['count1 = 0'])
		;
		return $query;
	}
}
