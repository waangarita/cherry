<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;

/**
* TblCart Model
*
* @method \App\Model\Entity\TblCart get($primaryKey, $options = [])
* @method \App\Model\Entity\TblCart newEntity($data = null, array $options = [])
* @method \App\Model\Entity\TblCart[] newEntities(array $data, array $options = [])
* @method \App\Model\Entity\TblCart|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
* @method \App\Model\Entity\TblCart patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
* @method \App\Model\Entity\TblCart[] patchEntities($entities, array $data, array $options = [])
* @method \App\Model\Entity\TblCart findOrCreate($search, callable $callback = null, $options = [])
*
* @mixin \Cake\ORM\Behavior\TimestampBehavior
*/
class TblCartTable extends Table
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

    $this->table('tbl_cart');
    $this->displayField('id');
    $this->primaryKey('id');

    $this->addBehavior('Timestamp');

    $this->belongsTo('AdmUser', [
            'foreignKey' => 'id_user',
            'joinType' => 'INNER'
        ]);

    $this->belongsTo('TblProduct', [
        'foreignKey' => 'id_product',
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
    ->integer('id')
    ->allowEmpty('id', 'create');

    $validator
    ->integer('id_user')
    ->requirePresence('id_user', 'create')
    ->notEmpty('id_user');

    $validator
    ->requirePresence('id_product', 'create')
    ->notEmpty('id_product');

    $validator
    ->integer('amount')
    ->requirePresence('amount', 'create')
    ->notEmpty('amount');

    return $validator;
  }

  public function getCartByUser($id_user) {
    return $this->find()
                ->select([
                  'TblCart.id', 'TblCart.amount', 'TblProduct.code',  'TblProduct.type_product', 
                  'TblProduct.product_series', 'TblProduct.models', 'TblProduct.id_family', 'TblProduct.img',
                  'price' => "(
                        SELECT lp.price AS price_product
                        FROM tbl_detail_list_price lp
                        INNER JOIN tbl_client c ON c.id_type_list_price = lp.id_list_price
                        WHERE lp.id_product = TblProduct.code
                        AND c.code = AdmUser.id_client
                      )"
                ])
                ->contain(['TblProduct', 'AdmUser'])
                ->where(['AdmUser.id' => $id_user]);
  }
}
