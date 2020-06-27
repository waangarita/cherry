<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TblPromotionSlide Model
 *
 * @method \App\Model\Entity\TblPromotionSlide get($primaryKey, $options = [])
 * @method \App\Model\Entity\TblPromotionSlide newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TblPromotionSlide[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TblPromotionSlide|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TblPromotionSlide patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TblPromotionSlide[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TblPromotionSlide findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TblPromotionSlideTable extends Table
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

        $this->table('tbl_promotion_slide');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        
        $this->belongsTo('AdmRole', [
          'foreignKey' => 'role_id'
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
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->integer('id_promotion')
            ->requirePresence('id_promotion', 'create')
            ->notEmpty('id_promotion');

        $validator
            ->requirePresence('img', 'create')
            ->notEmpty('img');

        $validator
            ->requirePresence('cta', 'create')
            ->notEmpty('cta');

        return $validator;
    }
}
