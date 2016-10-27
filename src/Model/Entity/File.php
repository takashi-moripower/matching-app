<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * File Entity
 *
 * @property int $id
 * @property int $information_id
 * @property string $name
 * @property string|resource $content
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Information $information
 */
class File extends Entity
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
	
	protected function _setTmpName( $value ){
		if( empty($value)){
			return;
		}
		$ret = file_get_contents($value);
		if ($ret === false) {
			throw new RuntimeException('Can not get file image');
		}		
		$this->content = $ret;
	}
	
	public function isImage(){
		$pattern ="/.[(png)(jpg)(jpeg)(bmp)(gif)]$/i";
		return preg_match( $pattern , $this->name );
	}
}
