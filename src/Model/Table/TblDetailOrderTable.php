<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TblDetailOrder Model
 *
 * @method \App\Model\Entity\TblDetailOrder get($primaryKey, $options = [])
 * @method \App\Model\Entity\TblDetailOrder newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TblDetailOrder[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TblDetailOrder|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TblDetailOrder patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TblDetailOrder[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TblDetailOrder findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TblDetailOrderTable extends Table
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

        $this->table('tbl_detail_order');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('TblProduct', [
          'foreignKey' => 'id_product'
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
            ->integer('id_order')
            ->requirePresence('id_order', 'create')
            ->notEmpty('id_order');

        $validator
            ->requirePresence('id_product', 'create')
            ->notEmpty('id_product');

        $validator
            ->decimal('amount')
            ->requirePresence('amount', 'create')
            ->notEmpty('amount');

        return $validator;
    }
}
