<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Enterprise Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $postalcode
 * @property string $address
 * @property string $phone
 * @property \Cake\I18n\Time $establish
 * @property int $capital
 * @property int $employee
 * @property \Cake\I18n\Time $crated
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Contact[] $contacts
 * @property \App\Model\Entity\Attribute[] $attributes
 */
class Enterprise extends Entity
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
}
