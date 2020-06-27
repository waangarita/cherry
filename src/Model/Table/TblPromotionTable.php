<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TblPromotion Model
 *
 * @method \App\Model\Entity\TblPromotion get($primaryKey, $options = [])
 * @method \App\Model\Entity\TblPromotion newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TblPromotion[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TblPromotion|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TblPromotion patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TblPromotion[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TblPromotion findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TblPromotionTable extends Table
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

        $this->table('tbl_promotion');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('TblPromotionSlide', ['className' => 'TblPromotionSlide','foreignKey' => 'id_promotion', 'propertyName' => 'sliders']);
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
            ->requirePresence('section', 'create')
            ->notEmpty('section');

        return $validator;
    }
}
