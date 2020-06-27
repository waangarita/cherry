<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;

/**
* TblOrder Model
*
* @method \App\Model\Entity\TblOrder get($primaryKey, $options = [])
* @method \App\Model\Entity\TblOrder newEntity($data = null, array $options = [])
* @method \App\Model\Entity\TblOrder[] newEntities(array $data, array $options = [])
* @method \App\Model\Entity\TblOrder|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
* @method \App\Model\Entity\TblOrder patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
* @method \App\Model\Entity\TblOrder[] patchEntities($entities, array $data, array $options = [])
* @method \App\Model\Entity\TblOrder findOrCreate($search, callable $callback = null, $options = [])
*
* @mixin \Cake\ORM\Behavior\TimestampBehavior
*/
class TblOrderTable extends Table
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

    $this->table('tbl_order');
    $this->displayField('id');
    $this->primaryKey('id');
    $this->addBehavior('Timestamp');

    $this->hasMany('TblDetailOrder', ['className' => 'TblDetailOrder','foreignKey' => 'id_order', 'propertyName' => 'details']);
  }

  /**
  * Default validation rules.
  *
  * @param \Cake\Validation\Validator $validator Validator instance.
  * @return \Cake\Validation\Validator
  */
  public function validationDefault(Validator $validator) {
    $validator
    ->integer('id')
    ->allowEmpty('id', 'create');

    $validator
    ->integer('id_user')
    ->requirePresence('id_user', 'create')
    ->notEmpty('id_user');

    $validator
    ->allowEmpty('status');

    return $validator;
  }

  public function detailOrder ($idUser, $idOrder, $top=0) {
    $connection = ConnectionManager::get('default');
    $limit = '';
    if ($top > 0) {
      $limit = 'Limit ' . $top;
    }

    $sqlDetail = sprintf("SELECT *,
                        (
                          SELECT lp.price AS price_product
                          FROM tbl_detail_list_price lp
                          INNER JOIN tbl_client c ON c.id_type_list_price = lp.id_list_price
                          INNER JOIN adm_user u ON u.id_client = c.code
                          WHERE lp.id_product = d.id_product
                          AND u.id = '%s'
                        ) AS price_product
                        FROM tbl_detail_order d
                        INNER JOIN tbl_order o on d.id_order = o.id
                        INNER JOIN tbl_product p ON d.id_product = p.code
                        WHERE id_order = '%s' %s ;",$idUser, $idOrder, $limit);
                        $result = $connection->execute($sqlDetail)->fetchAll('assoc');
                        return $result;
  }

  public function createCodeOrder ($id, $idUser, $idClient) {
    $orderId ='';

    // buscamos el faltante para llegar a 10 digitos
    $faltante = 10 - strlen($id);

    // iteramos con un for las veces que falten
    for ($i=1; $i<=$faltante; $i++) {
      $orderId = $orderId . '0';
    }

    // concatenamos codigo de ceros con el id
    $orderId = $orderId . $id;

    // retornamos el codigo
    return $idUser.'-'.$idClient.'-'.$orderId;

  }

  public function countOrders($month=0, $year=0) {
    $connection = ConnectionManager::get('default');

    $query = "SELECT count(*) AS cuantos FROM tbl_order WHERE created IS NOT NULL ";

    if($month <> 0) {
      $query .= sprintf('AND month(created) = %s ', $month);
    }

    if($year <> 0) {
      $query .= sprintf('AND year(created) = %s ', $year);
    }

    try {
      $result = $connection->execute($query)->fetchAll('assoc');
      return $result;
    } catch(\Exception $e) {
      $connection->disconnect();
      $this->log($e->getMessage(), "error");
      return false;
    }
  }

}
