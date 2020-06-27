<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AdmMenu2role Entity
 *
 * @property int $menu_id
 * @property int $role_id
 * @property \Cake\I18n\Time $created
 *
 * @property \App\Model\Entity\Menu $menu
 * @property \App\Model\Entity\Role $role
 */
class AdmMenu2role extends Entity
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
        'menu_id' => false,
        'role_id' => false
    ];
}
