<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Log\Log;

/**
 * AdmRole Model
 *
 * @method \App\Model\Entity\AdmRole get($primaryKey, $options = [])
 * @method \App\Model\Entity\AdmRole newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AdmRole[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AdmRole|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AdmRole patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AdmRole[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AdmRole findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AdmRoleTable extends Table
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

        $this->table('adm_role');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('AdmMenu2Role', ['className' => 'AdmMenu2Role','foreignKey' => 'role_id', 'propertyName' => 'Menus']);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        return $validator;
    }

    public function findRegularRoles(){
        return $this->find()->where(['name != ' => 'superadmin']);
        // return $this->find()->where();
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
