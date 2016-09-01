<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Auth\DefaultPasswordHasher;
use App\Defines\Defines;
/**
 * User Entity.
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property int $group_id
 * @property \App\Model\Entity\Group $group
 * @property string $facebook
 * @property string $google
 * @property string $twitter
 * @property int $expunge
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property \App\Model\Entity\Comment[] $comments
 * @property \App\Model\Entity\Enterprise[] $enterprises
 * @property \App\Model\Entity\Information[] $informations
 */
class User extends Entity
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
        'id' => false,
    ];

    /**
     * Fields that are excluded from JSON an array versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];
	
	
    protected function _setPassword($password)
    {
		if( $password == '' ){
			return $this->password;
		}
        return (new DefaultPasswordHasher)->hash($password);
    }

    public function parentNode()
    {
        if (!$this->id) {
            return null;
        }
        if (isset($this->group_id)) {
            $group_id = $this->group_id;
        } else {
            $users_table = TableRegistry::get('Users');
            $user = $users_table->find('all', ['fields' => ['group_id']])->where(['id' => $this->id])->first();
            $group_id = $user->group_id;
        }
        if (!$group_id) {
            return null;
        }

        return ['Groups' => ['id' => $group_id]];
    }
		
	protected function _getEnterprise($value){
		if( isset( $value )){
			return $value;
		}
		
		$enterprise = TableRegistry::get('Enterprises')->find()
				->where(['user_id'=>$this->id])
				->first();
		
		$this->enterprise = $enterprise;
		return $enterprise;
	}
	
	protected function _getEngineer( $value ){
		if( isset( $value )){
			return $value;
		}
		
		$engineer = TableRegistry::get('Engineers')->find()
				->where(['user_id'=>$this->id])
				->first();
		
		$this->engineer = $engineer;
		return $engineer;
	}
}
