<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;

/**
* TblCrossReferences Model
*
* @method \App\Model\Entity\TblCrossReference get($primaryKey, $options = [])
* @method \App\Model\Entity\TblCrossReference newEntity($data = null, array $options = [])
* @method \App\Model\Entity\TblCrossReference[] newEntities(array $data, array $options = [])
* @method \App\Model\Entity\TblCrossReference|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
* @method \App\Model\Entity\TblCrossReference patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
* @method \App\Model\Entity\TblCrossReference[] patchEntities($entities, array $data, array $options = [])
* @method \App\Model\Entity\TblCrossReference findOrCreate($search, callable $callback = null, $options = [])
*
* @mixin \Cake\ORM\Behavior\TimestampBehavior
*/
class TblCrossReferencesTable extends Table
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

    $this->table('tbl_cross_references');
    $this->displayField('id_product');
    $this->primaryKey(['id_product', 'id_product_related']);
    $this->addBehavior('Timestamp');
    $this->belongsTo('TblProduct', [
      'foreignKey' => 'id_product'
    ]);
    $this->belongsTo('TblProduct', [
      'foreignKey' => 'id_product_related'
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
    ->allowEmpty('id_product_related', 'create');

    return $validator;
  }


  /**
   * getCrossReferences Trae los productos relacionados de un producto en especifico , cuando es diferente a toner tiene que traer el producto padre
   *
   * @param  $id_product Producto especifico.
   * @param  $id_client para poder traer el precio de la lista de precios
   * @param  $id_user para saber si alguno de esos productos existe en el carrito
   * @return void
   */
  public function getCrossReferences($id_product, $id_client, $id_user) {
    $connection = ConnectionManager::get('default');
    $sql = "SELECT count(id_product_related) AS cuantos
            FROM tbl_cross_references
            WHERE id_product = '%s' ";
    $buscarProduct = sprintf($sql, $id_product);
    $cuantos = $connection->execute($buscarProduct)->fetchAll('assoc');

    if($cuantos[0]['cuantos'] == 0) {
      $conditions [] = array('TblCrossReferences.id_product_related' => $id_product);
    } else {
      $conditions [] = array('TblCrossReferences.id_product' => $id_product);
    }

    $products = $this->find()
                      ->select([
                        'TblProduct.code', 'TblProduct.type_product', 'TblProduct.product_series', 
                        'TblProduct.models', 'TblProduct.status', 'TblProduct.id_family', 'TblProduct.img',
                        'iscart' => sprintf("(
                              CASE
                              WHEN
                                (SELECT count(crt.id) FROM tbl_cart crt WHERE crt.id_product = TblProduct.code AND crt.id_user = '%s') > 0
                              THEN 1 ELSE 0
                              END
                            )", $id_user),
                        'price_product' => sprintf("(
                              SELECT lp.price AS price_product
                              FROM tbl_detail_list_price lp
                              INNER JOIN tbl_client c ON c.id_type_list_price = lp.id_list_price
                              WHERE lp.id_product = TblProduct.code
                              AND c.code = '%s'
                            )", $id_client)
                      ])
                      ->contain(['TblProduct'])
                      ->where($conditions);
    return $products;
  }

  /**
   * Log info
   * @param $str. The info to log
   * @param $level. The logging level
   */
   private function log($str, $level){
     Log::write($level, $str, get_class($this));
   }

}
