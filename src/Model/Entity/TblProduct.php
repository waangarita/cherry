<?php
namespace App\Model\Entity;
use Cake\ORM\Entity;

/**
 * TblProduct Entity
 *
 * @property string $code
 * @property string $id_family
 * @property string $type_product
 * @property string $product_series
 * @property string $models
 * @property string $presentation
 * @property float $per_master
 * @property string $weight
 * @property string $status
 * @property bool $active
 * @property float $price_suggested
 * @property string $img
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class TblProduct extends Entity
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
        'code' => false
    ];

    protected function _getImg($value)
    {
        $img = 'images/products/'.$value;

        if ( file_exists(WWW_ROOT. $img) ) {
            $imagen = DS.$img;
        } else if( file_exists(WWW_ROOT. 'images/products/'.$this->id_family.'_default.jpg') ) {
            $imagen = DS.'images/products/'.$this->id_family.'_default.jpg';
        } else {
            $imagen = DS.'images/products/no-image.jpg';
        }

        return $imagen;
    }
}
