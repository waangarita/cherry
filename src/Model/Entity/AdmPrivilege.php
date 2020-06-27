<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AdmPrivilege Entity
 *
 * @property int $role_id
 * @property int $section_id
 * @property bool $can_list
 * @property bool $can_create
 * @property bool $can_edit
 * @property bool $can_delete
 * @property bool $can_view
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class AdmPrivilege extends Entity
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
        'role_id' => false,
        'section_id' => false
    ];
}
