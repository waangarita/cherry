<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Log\Log;

/**
 * AdmMenu2role Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Menus
 * @property \Cake\ORM\Association\BelongsTo $Roles
 *
 * @method \App\Model\Entity\AdmMenu2role get($primaryKey, $options = [])
 * @method \App\Model\Entity\AdmMenu2role newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AdmMenu2role[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AdmMenu2role|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AdmMenu2role patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AdmMenu2role[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AdmMenu2role findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AdmMenu2RoleTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('adm_menu2role');
        $this->displayField('menu_id');
        $this->primaryKey(['menu_id', 'role_id']);

        $this->addBehavior('Timestamp');

        $this->belongsTo('AdmMenu', [
            'foreignKey' => 'menu_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('AdmRole', [
            'foreignKey' => 'role_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['menu_id'], 'AdmMenu'));
        $rules->add($rules->existsIn(['role_id'], 'AdmRole'));

        return $rules;
    }

    public function deleteAllByMenuId($id){
        return $this->deleteAll(['menu_id' => $id]);
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
