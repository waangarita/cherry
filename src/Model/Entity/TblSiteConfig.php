<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TblSiteConfig Entity
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $cta1
 * @property string $slide1
 * @property string $slide_mobile1
 * @property string $cta2
 * @property string $slide2
 * @property string $slide_mobile2
 * @property string $cta3
 * @property string $slide3
 * @property string $body
 * @property string $slide_mobile3
 * @property string $banner
 * @property string $banner_mobile
 * @property string $cta_banner
 * @property \Cake\I18n\Time $created
 */
class TblSiteConfig extends Entity
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


    public function isTargetBlank1(){
      $url = $this->cta1;
      if(empty($url))
      return false;
      else
      return ((strpos($url, 'http://') !== false) || (strpos($url, 'https://') !== false) || (strpos($url, 'wwww.') !== false));
    }

    public function isTargetBlank2(){
      $url = $this->cta2;
      if(empty($url))
      return false;
      else
      return ((strpos($url, 'http://') !== false) || (strpos($url, 'https://') !== false) || (strpos($url, 'wwww.') !== false));
    }

    public function isTargetBlank3(){
      $url = $this->cta3;
      if(empty($url))
      return false;
      else
      return ((strpos($url, 'http://') !== false) || (strpos($url, 'https://') !== false) || (strpos($url, 'wwww.') !== false));
    }
}
