<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Defines\Defines;

/**
 * Comment Entity
 *
 * @property int $id
 * @property int $engineer_id
 * @property int $enterprise_id
 * @property int $type
 * @property string $content
 *
 * @property \App\Model\Entity\Engineer $engineer
 * @property \App\Model\Entity\Enterprise $enterprise
 */
class Comment extends Entity {

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
	
	protected function _getSenderName( $value ){
		$flag_sender = $this->flags & Defines::COMMENT_FLAG_SEND_MASK;
		switch( $flag_sender ){
			case Defines::COMMENT_FLAG_SEND_ADMINISTRATOR:
				return '管理者';
				
			case Defines::COMMENT_FLAG_SEND_ENGINEER:
				return $this->engineer->user->name;
				
			case Defines::COMMENT_FLAG_SEND_ENTERPRISE:
				return $this->enterprise->user->name;
		}
	}

}
