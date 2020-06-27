<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TblCrossReference Entity
 *
 * @property string $id_product
 * @property string $id_product_related
 * @property \Cake\I18n\Time $created
 */
class TblCrossReference extends Entity
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
        'id_product' => false,
        'id_product_related' => false
    ];
}
