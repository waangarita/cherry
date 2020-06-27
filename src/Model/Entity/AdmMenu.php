<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Log\Log;

/**
 * AdmMenu Entity
 *
 * @property int $id
 * @property int $menu_id
 * @property string $display_name
 * @property int $position
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\AdmMenu $Parent
 * @property \App\Model\Entity\AdmMenu2Role[] $Roles
 */
class AdmMenu extends Entity
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

    public function enabledForUser($auth){
        $style = 'style="display: none !important;"';
        foreach ($this->Roles as $menu2role) {
            if($menu2role->role_id == $auth['role_id'])
                $style = '';
        }
        
        return $style;
    }

    /**
    * Log info
    * @param $str. The info to log
    * @param $level. The logging level
    */
    private function log($str, $level)
    {
        Log::write($level, $str);
    }
}
