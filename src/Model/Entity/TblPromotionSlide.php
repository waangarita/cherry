<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TblPromotionSlide Entity
 *
 * @property int $id
 * @property int $id_promotion
 * @property string $img
 * @property string $cta
 * @property \Cake\I18n\Time $created
 */
class TblPromotionSlide extends Entity
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

    public function isTargetBlank () {
      $url = $this->cta;
      if(empty($url))
      return false;
      else
      return ((strpos($url, 'http://') !== false) || (strpos($url, 'https://') !== false) || (strpos($url, 'wwww.') !== false));
    }
}
