<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Hash;
use App\Defines\Defines;

/**
 * Offer Entity
 *
 * @property int $id
 * @property int $enterprise_id
 * @property string $title
 * @property string $note
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Enterprise $enterprise
 * @property \App\Model\Entity\Attribute[] $attributes
 */
class Offer extends Entity {

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

	protected function _getOpt($value) {
		if (empty($this->options)) {
			return [];
		} else {
			return unserialize($this->options);
		}
	}

	protected function _setOpt($value) {
		$this->options = serialize($value + (array) $this->options);
	}

	public function getOption($path, $default = NULL) {
		return Hash::get($this->opt, $path, $default);
	}

	public function setOption($path, $value) {
		$this->opt = Hash::insert($this->opt, $path, $value);
	}

	public function getSearchRequest() {
		$request = [];
		$values = [];

		foreach ($this->attributes as $attribute) {
			if (empty($values[$attribute->type])) {
				$values[$attribute->type] = [];
			}
			$values[$attribute->type][] = $attribute->id;
		}

		for ($type = 1; $type <= Defines::ATTRIBUTE_TYPE_MAX; $type++) {
			$key = "operation{$type}";
			$request = Hash::insert($request, "attributes.{$type}.operation", (int) $this->{$key});
			$request = Hash::insert($request, "attributes.{$type}.values", Hash::get($values, $type, []));
		}
		return $request;
	}

	protected function _getAttributeText($value) {

		$result = '';
		foreach (Defines::ATTRIBUTE_TYPE_NAME as $type_id => $type_name) {
			$typedText = $this->_getTypedAttributeText($type_id);
			if( !empty( $result ) && !empty( $typedText )){
				$result .= ',';
			}
			$result .=  $typedText;
		}

		return $result;
	}

	public function getTypedAttributes($type_id) {

		return array_filter($this->attributes, function ($attribute) use( $type_id ) {
			return $attribute->type == $type_id;
		});
	}

	protected function _getTypedAttributeText($type_id) {
		$attributes = $this->getTypedAttributes($type_id);
		$result = '';

		if (empty($attributes)) {
			return $result;
		}

		$operation_key = "operation{$type_id}";

//		if ($this->{$operation_key} == Defines::OFFER_OPERATION_AND ) {
		if ($this->{$operation_key} == Defines::OFFER_OPERATION_AND || count( $attributes) == 1 ) {
			$result = implode(',', array_map(function($a) {
						return $a->name;
					}, $attributes));
		} else {
			$result = '[' . implode('|', array_map(function($a) {
								return $a->name;
							}, $attributes)) . ']';
		}

		return $result;
	}

}
