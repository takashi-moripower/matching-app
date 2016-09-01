<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\I18n\Date;
use Cake\Utility\Hash;
use App\Defines\Defines;

/**
 * Engineer Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $postalcode
 * @property string $address
 * @property string $phone
 * @property \Cake\I18n\Time $birthday
 * @property string $education
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\AttributesEnterprise[] $attributes_enterprises
 * @property \App\Model\Entity\Comment[] $comments
 * @property \App\Model\Entity\Contact[] $contacts
 */
class Engineer extends Entity {

	/**
	 * Fields that can be mass assigned using newEntity() or patchEntity().
	 *
	 * Note that when '*' is set to true, this allows all unspecified fields to
	 * be mass assigned. For security purposes, it is advised to set '*' to false
	 * (or remove it), and explicitly make individual fields accessible as needed.
	 *
	 * @var array
	 */
	protected $_accessible = [
		'*' => true,
		'id' => false
	];

	protected function _getAttributes($value) {
		if (!empty($value)) {
			return $value;
		}

		$table_a = TableRegistry::get('Attributes');
		$table_ae = TableRegistry::get('AttributesEngineers');

		$list_a = $table_ae->find()
				->where(['engineer_id' => $this->id])
				->select(['attribute_id']);

		$attributes = $table_a->find()
				->where(['id in' => $list_a])
				->order(['type' => 'ASC', 'id' => 'ASC'])
				->toArray();

		$this->attributes = $attributes;
		return $attributes;
	}

	protected function _getAttributeNames($value) {
		if (!empty($value)) {
			return $value;
		}

		$result = implode(',', array_map(function($a) {
					return $a->name;
				}, $this->attributes));

		$this->attribute_name = $result;
		return $result;
	}

	protected function _getAge($value) {
		return $this->birthday->diff(Date::now())->format('%y');
	}

	public function getSearchRequest() {

		$request = [];
		foreach ($this->attributes as $attribute) {
			$count = count( Hash::get($request,  "attributes.{$attribute->type}",  [] ) );
			$request = Hash::insert( $request , "attributes.{$attribute->type}.{$count}" , $attribute->id );
		}

		return $request;
	}

}
