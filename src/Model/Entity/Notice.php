<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Hash;
use App\Defines\Defines;
/**
 * Notice Entity
 *
 * @property int $id
 * @property string $content
 * @property \Cake\I18n\Time $start
 * @property \Cake\I18n\Time $end
 * @property int $flags
 */
class Notice extends Entity {

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

	protected function _getFlagsText(){
		return Hash::get( Defines::NOTICE_FLAGS , $this->flags , 'undefined');
	}
	
	protected function _getStartSet(){
		return !empty( $this->start ) ;
	}
	
	protected function _setStartSet( $value ){
		if( empty($value) ){
			$this->start = NULL;
		}
	}
	
	protected function _getEndSet(){
		return !empty( $this->end ) ;
	}
	
	protected function _setEndSet( $value ){
		if( empty($value) ){
			$this->end = NULL;
		}
	}
	
}
