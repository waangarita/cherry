<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Log\Log;

/**
 * AdmMenu Model
 *
 * @property \Cake\ORM\Association\BelongsTo $AdmMenu
 *
 * @method \App\Model\Entity\AdmMenu get($primaryKey, $options = [])
 * @method \App\Model\Entity\AdmMenu newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AdmMenu[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AdmMenu|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AdmMenu patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AdmMenu[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AdmMenu findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AdmMenuTable extends Table
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

        $this->table('adm_menu');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('AdmMenuChild', ['className' => 'AdmMenu','foreignKey' => 'menu_id', 'propertyName' => 'Childs']);
        $this->belongsTo('AdmMenuParent', ['className' => 'AdmMenu','foreignKey' => 'menu_id', 'propertyName' => 'Parent']);
        $this->belongsTo('AdmSection', ['className' => 'AdmSection','foreignKey' => 'section_id', 'propertyName' => 'Section']);
        $this->hasMany('AdmMenu2Role', ['className' => 'AdmMenu2Role','foreignKey' => 'menu_id', 'propertyName' => 'Roles']);
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
            ->requirePresence('display_name', 'create')
            ->notEmpty('display_name');

        $validator
            ->integer('position')
            ->requirePresence('position', 'create')
            ->notEmpty('position');

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
        $rules->add($rules->existsIn(['menu_id'], 'AdmMenuParent'));

        return $rules;
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
