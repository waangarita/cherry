<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TblCart Entity
 *
 * @property int $id
 * @property int $id_user
 * @property string $id_product
 * @property int $amount
 * @property \Cake\I18n\Time $created
 */
class TblCountry extends Entity
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
