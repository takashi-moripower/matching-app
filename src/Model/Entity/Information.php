<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
/**
 * Information Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $order
 * @property string $title
 * @property string $content
 * @property int $published
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\File[] $files
 */
class Information extends Entity
{

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
	
	protected function _getCountFiles( $value ){
		if( $value == NULL ){
			$count = TableRegistry::get('Files')->find()
					->where(['information_id'=>$this->id])
					->count();
			$this->count_files = $count;
			return $count;
		}
		
		return $value;
	}
}
