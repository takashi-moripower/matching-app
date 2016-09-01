<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Defines\Defines;

/**
 * Contact Entity
 *
 * @property int $id
 * @property int $engineer_id
 * @property int $enterprise_id
 * @property int $engineer_access
 * @property \Cake\I18n\Time $engineer_viewdate
 * @property int $engineer_interest
 * @property int $enterprise_access
 * @property \Cake\I18n\Time $enterprise_viewdate
 * @property string $enterprise_interest
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Engineer $engineer
 * @property \App\Model\Entity\Enterprise $enterprise
 */
class Contact extends Entity {

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
	
	protected function _getRecordText( $value ){
		if( empty($value )){
			return 'なし';
		}
		
		$access_name = Defines::CONTACT_RECORD_NAME;
		krsort($access_name);
		
		$result = '';
		foreach( $access_name as $flag => $title ){
			if( $value & $flag ){
				if( !empty( $result )){
					$result .= ',';
				}
				$result .= $title;
			}
		}
		
		return $result;
	}

	protected function _getEnterpriseRecordText( $value ){
		return $this->_getRecordText( $this->enterprise_record );
	}
	
	protected function _getEngineerRecordText( $value ){
		return $this->_getRecordText( $this->engineer_record );
	}
}
