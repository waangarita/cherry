<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;

/**
 * AdmSection Model
 *
 * @method \App\Model\Entity\AdmSection get($primaryKey, $options = [])
 * @method \App\Model\Entity\AdmSection newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AdmSection[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AdmSection|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AdmSection patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AdmSection[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AdmSection findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AdmSectionTable extends Table
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

        $this->table('adm_section');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasOne('AdmPrivilege', ['className' => 'AdmPrivilege','foreignKey' => 'section_id', 'propertyName' => 'Privilege']);
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

        $validator
            ->requirePresence('controller', 'create')
            ->notEmpty('controller');

        $validator
            ->requirePresence('action', 'create')
            ->notEmpty('action');

        return $validator;
    }

    public function findSectionsEnabledForRoleId($id){
        $connection = ConnectionManager::get('default');
        $query = 'SELECT *
                  FROM adm_section section
                  WHERE section.id NOT IN (SELECT section_id FROM adm_privilege WHERE role_id = ?)';

        $results = $connection->execute($query,[$id],['integer'])->fetchAll('assoc');
        return $results;
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
