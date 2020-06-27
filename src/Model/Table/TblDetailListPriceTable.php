<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TblDetailListPrice Model
 *
 * @method \App\Model\Entity\TblDetailListPrice get($primaryKey, $options = [])
 * @method \App\Model\Entity\TblDetailListPrice newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TblDetailListPrice[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TblDetailListPrice|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TblDetailListPrice patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TblDetailListPrice[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TblDetailListPrice findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TblDetailListPriceTable extends Table
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

        $this->table('tbl_detail_list_price');
        $this->displayField('id_product');
        $this->primaryKey(['id_product', 'id_list_price']);

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
            ->allowEmpty('id_product', 'create');

        $validator
            ->integer('id_list_price')
            ->allowEmpty('id_list_price', 'create');

        $validator
            ->decimal('price')
            ->requirePresence('price', 'create')
            ->notEmpty('price');

        return $validator;
    }
}
