<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Log\Log;

/**
 * AdmPrivilege Model
 *
 * @property \Cake\ORM\Association\BelongsTo $AdmRole
 * @property \Cake\ORM\Association\BelongsTo $AdmSection
 *
 * @method \App\Model\Entity\AdmPrivilege get($primaryKey, $options = [])
 * @method \App\Model\Entity\AdmPrivilege newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AdmPrivilege[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AdmPrivilege|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AdmPrivilege patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AdmPrivilege[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AdmPrivilege findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AdmPrivilegeTable extends Table
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

        $this->table('adm_privilege');
        $this->displayField('role_id');
        $this->primaryKey(['role_id', 'section_id']);

        $this->addBehavior('Timestamp');

        $this->belongsTo('AdmRole', [
            'foreignKey' => 'role_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('AdmSection', [
            'foreignKey' => 'section_id',
            'joinType' => 'INNER'
        ]);
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
            ->boolean('can_list')
            ->requirePresence('can_list', 'create')
            ->notEmpty('can_list');

        $validator
            ->boolean('can_create')
            ->requirePresence('can_create', 'create')
            ->notEmpty('can_create');

        $validator
            ->boolean('can_edit')
            ->requirePresence('can_edit', 'create')
            ->notEmpty('can_edit');

        $validator
            ->boolean('can_delete')
            ->requirePresence('can_delete', 'create')
            ->notEmpty('can_delete');

        $validator
            ->boolean('can_view')
            ->requirePresence('can_view', 'create')
            ->notEmpty('can_view');

        return $validator;
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
        $rules->add($rules->existsIn(['role_id'], 'AdmRole'));
        $rules->add($rules->existsIn(['section_id'], 'AdmSection'));

        return $rules;
    }

    public function deleteAllByRoleId($id){
        return $this->deleteAll(['role_id' => $id]);
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
