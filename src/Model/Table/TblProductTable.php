<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;

/**
* TblProducts Model
*
* @method \App\Model\Entity\TblProduct get($primaryKey, $options = [])
* @method \App\Model\Entity\TblProduct newEntity($data = null, array $options = [])
* @method \App\Model\Entity\TblProduct[] newEntities(array $data, array $options = [])
* @method \App\Model\Entity\TblProduct|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
* @method \App\Model\Entity\TblProduct patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
* @method \App\Model\Entity\TblProduct[] patchEntities($entities, array $data, array $options = [])
* @method \App\Model\Entity\TblProduct findOrCreate($search, callable $callback = null, $options = [])
*
* @mixin \Cake\ORM\Behavior\TimestampBehavior
*/
class TblProductTable extends Table
{

  /**
  * Initialize method
  *
  * @param array $config The configuration for the Table.
  * @return void
  */
  public function initialize (array $config)
  {
    parent::initialize($config);

    $this->table('tbl_product');
    $this->displayField('code');
    $this->primaryKey('code');

    $this->addBehavior('Timestamp');
    $this->belongsTo('TblFamily', [
      'foreignKey' => 'id_family'
    ]);
  }

  /**
  * Default validation rules.
  *
  * @param \Cake\Validation\Validator $validator Validator instance.
  * @return \Cake\Validation\Validator
  */
  public function validationDefault (Validator $validator) {
    $validator
    ->allowEmpty('code', 'create');

    $validator
    ->requirePresence('id_family', 'create')
    ->notEmpty('id_family');

    $validator
    ->requirePresence('type_product', 'create')
    ->notEmpty('type_product');


    $validator
    ->requirePresence('presentation', 'create')
    ->notEmpty('presentation');


    $validator
    ->requirePresence('status', 'create')
    ->notEmpty('status');

    $validator
    ->boolean('active')
    ->requirePresence('active', 'create')
    ->notEmpty('active');


    $validator
    ->requirePresence('img', 'create')
    ->notEmpty('img');

    return $validator;
  }

  /**
  * loadDataLocalInfile Upload products infile
  *
  * @param $File route of file
  * @param $hash code md5 generated from identify upload
  * @return True or False
  */
  public function loadDataLocalInfile ($file, $hash) {
    ini_set('memory_limit', '1024M');
    ini_set('auto_detect_line_endings', true);
    $connection = ConnectionManager::get('default');
    $fieldsTerminatedIn = $this->detectDelimiter($file);
    $linesTerminatedIn = $fieldsTerminatedIn == ',' ? '\r' : '\n';
    $query = sprintf("CREATE TEMPORARY TABLE tbl_product_tmp LIKE tbl_product;
                      LOAD DATA LOCAL INFILE '%s'
                      IGNORE
                      INTO TABLE tbl_product_tmp
                      FIELDS TERMINATED BY '%s' ENCLOSED BY ''
                      LINES TERMINATED BY '%s'
                      IGNORE 1 LINES
                      (code, id_family, type_product, product_series, models, presentation, per_master, weight, status, price_suggested)
                      SET active = 1;",$file,$fieldsTerminatedIn,$linesTerminatedIn);

    $query2 = "INSERT IGNORE INTO tbl_product
                SELECT * FROM tbl_product_tmp
                ON DUPLICATE KEY UPDATE  tbl_product.price_suggested = tbl_product_tmp.price_suggested ,tbl_product.type_product = tbl_product_tmp.type_product,
                tbl_product.product_series = tbl_product_tmp.product_series,tbl_product.models=tbl_product_tmp.models,
                tbl_product.presentation=tbl_product_tmp.presentation,tbl_product.per_master=tbl_product_tmp.per_master,tbl_product.weight=tbl_product_tmp.weight,
                tbl_product.status='NA',tbl_product.active=1;";
    
    $query3 = sprintf("INSERT INTO tbl_file_upload (hash,name,type,records,records_updated) 
                      VALUES ('%s','%s','PRODUCT',(SELECT count(*) FROM tbl_product_tmp),(SELECT count(*) FROM tbl_product WHERE active=1)); ", $hash, $file);
    
    $query4 = "DROP TEMPORARY TABLE tbl_product_tmp;";

    try {
      $connection->begin();
      $connection->execute($query);
      $connection->execute($query2);
      $connection->execute($query3);
      $connection->execute($query4);
      $connection->commit();
      return true;
    } catch(\Exception $e) {
      $connection->rollback();
      $connection->disconnect();
      $this->log($e->getMessage(), "error");
      return false;
    }
  }

  /**
  * loadDataCrossReferences Upload cross references of products
  *
  * @param $File route of file
  * @param $hash code md5 generated from identify upload cross references
  * @return True or False
  */
  public function loadDataCrossReferences ($file, $hash) {
    ini_set('memory_limit', '1024M');
    ini_set('auto_detect_line_endings', true);
    $connection = ConnectionManager::get('default');
    $fieldsTerminatedIn = $this->detectDelimiter($file);
    $linesTerminatedIn = $fieldsTerminatedIn == ',' ? '\r' : '\n';
    $query = sprintf("CREATE TEMPORARY TABLE tbl_cross_references_tmp LIKE tbl_cross_references;
                      LOAD DATA LOCAL INFILE '%s'
                      IGNORE
                      INTO TABLE tbl_cross_references_tmp
                      FIELDS TERMINATED BY '%s' ENCLOSED BY ''
                      LINES TERMINATED BY '%s'
                      IGNORE 1 LINES
                      (id_product, id_product_related);", $file, $fieldsTerminatedIn, $linesTerminatedIn);

    $query2 = "INSERT IGNORE INTO tbl_cross_references
              SELECT * FROM tbl_cross_references_tmp
              ON DUPLICATE KEY UPDATE  tbl_cross_references.id_product = tbl_cross_references_tmp.id_product,
              tbl_cross_references.id_product_related = tbl_cross_references_tmp.id_product_related;";

    $query3 = sprintf("INSERT INTO tbl_file_upload
              (hash,name,type,records,records_updated)
              VALUES ('%s','%s','OTHER',(SELECT count(*) FROM tbl_cross_references_tmp),(SELECT count(*) FROM tbl_cross_references));", $hash, $file);

    $query4 = "DROP TEMPORARY TABLE tbl_cross_references_tmp;";

    try {
      $connection->begin();
      $connection->execute($query);
      $connection->execute($query2);
      $connection->execute($query3);
      $connection->execute($query4);
      $connection->commit();
      return true;
    } catch(\Exception $e) {
      $connection->rollback();
      $connection->disconnect();
      $this->log($e->getMessage(), "error");
      return false;
    }
  }

  /**
  * huerfanos, Buscar todos los productos que no tengan padre relacionado o el padre relacionado no exista
  * @return Todos los productos sin padre relacionado
  */
  public function huerfanos () {
    $connection = ConnectionManager::get('default');
    /* $query = "SELECT id_product, id_product_related
              FROM tbl_cross_references
              WHERE id_product <> 0
              AND id_product NOT IN (SELECT code FROM tbl_product WHERE active=1);"; */

    $query = "SELECT id_product_related
              FROM tbl_cross_references
              WHERE id_product = 0
              AND id_product_related != 0";
    try {
      $result = $connection->execute($query)->fetchAll('assoc');
      return $result;
    } catch (Exception $e) {
      $this->log($e->getMessage(), "error");
      return false;
    }
  }

  /**
  * getProductByStatus get products by status
  *
  * @param $status => Status product example = N:NUEVO, D:DESCUENTO, L:LIQUIDADO, PM:PRECIO MEJORADO
  * @param $id_client => Code company of user login
  * @param $top => Quantity of products request
  * @param $user_id => code user login  from get precio Product by list products
  * @return array products
  */
  public function getProductByStatus ($status, $id_client, $top=100, $user_id) {
     return $this->find()
                ->select([
                  'TblProduct.code', 'TblProduct.type_product', 'TblProduct.product_series', 
                  'TblProduct.models', 'TblProduct.status', 'TblProduct.id_family', 'TblProduct.img',
                  'iscart' => sprintf("(
                        CASE
                        WHEN
                          (SELECT count(crt.id) FROM tbl_cart crt WHERE crt.id_product = TblProduct.code AND crt.id_user = '%s') > 0
                        THEN 1 ELSE 0
                        END
                      )", $user_id),
                  'price_product' => sprintf("(
                        SELECT lp.price AS price_product
                        FROM tbl_detail_list_price lp
                        INNER JOIN tbl_client c ON c.id_type_list_price = lp.id_list_price
                        WHERE lp.id_product = TblProduct.code
                        AND c.code = '%s'
                      )", $id_client)
                ])
                ->where(['TblProduct.active' => 1, 'TblProduct.status' => $status])
                ->order('TblProduct.code')
                ->limit($top);
  }

  /**
  * getProductsByfamily get products by family
  *
  * @param $family => code family
  * @param $id_client => Code company of user login
  * @param $user_id => code user login from get precio Product by list products
  * @return array products
  */
  public function getProductsByfamily ($family, $id_client, $id_user) {
    return $this->find()
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
                ->where(['TblProduct.active' => 1, 'TblProduct.id_family' => $family])
                ->order('TblProduct.code');
  }

  /**
  * getAllProducts get all products from grid of products
  *
  * @param $id_client => Code company of user login
  * @param $user_id => code user login from get precio Product by list products
  * @return array products
  */
  public function getAllProducts ($id_client, $id_user) {
    return $this->find()
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
                ->where(['TblProduct.active' => 1])
                ->order('TblProduct.code');
  }

  /**
  * searchProducts search products by params
  *
  * @param $filter => words or code to search
  * @param $id_client => Code company of user login
  * @param $user_id => code user login from get precio Product by list products
  * @return array products
  */
  public function searchProducts ($filter, $id_client, $id_user) {
    return $this->find()
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
                ->join([
                  [
                    'table' => 'tbl_family',
                    'type' => 'INNER',
                    'conditions' => 'tbl_family.code = TblProduct.id_family'
                  ],
                  [
                    'table' => 'tbl_brand',
                    'type' => 'INNER',
                    'conditions' => 'tbl_brand.code = tbl_family.id_brand'
                  ]
                ])
                ->where([
                  'TblProduct.active' => 1, 'OR' => [
                    ['TblProduct.code' => $filter],
                    ['tbl_brand.code' => $filter],
                    ['tbl_family.code' => $filter],
                    [sprintf("upper(TblProduct.type_product) LIKE upper('%%%s%%')", $filter)],
                    [sprintf("upper(tbl_brand.name) LIKE upper('%%%s%%')", $filter)],
                    [sprintf("upper(tbl_family.name) LIKE upper('%%%s%%')", $filter)],
                    [sprintf("upper(TblProduct.product_series) LIKE upper('%%%s%%')", $filter)],
                    [sprintf("upper(TblProduct.models) LIKE upper('%%%s%%')", $filter)]
                  ] 
                ])->order('TblProduct.code');
  }

  /**
  * searchProducts get detail by products
  *
  * @param $id_product => code products
  * @param $id_client => Code company of user login
  * @param $user_id => code user login from get precio Product by list products
  * @return array detail products
  */
  public function getDetailProduct ($id_product, $id_client, $id_user) {
     return $this->find()
                ->select([
                  'TblProduct.code', 'TblProduct.id_family', 'TblProduct.type_product', 'TblProduct.product_series', 'TblProduct.models', 
                  'TblProduct.presentation', 'TblProduct.per_master', 'TblProduct.weight', 'TblProduct.img', 'TblProduct.status', 
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
                ->where(['TblProduct.active' => 1, 'TblProduct.code' => $id_product]);
  }

  /**
  * getListProductByUser Catalogo de productos por usuario para el front
  *
  * @param $id_client => Code company of user login
  * @return array products
  */
  public function getListProductByUser ($id_client) {
    $connection = ConnectionManager::get('default');
    $query = sprintf("SELECT p.*,f.name AS family,
                      (
                        SELECT lp.price AS price_product
                        FROM tbl_detail_list_price lp
                        INNER JOIN tbl_client c ON c.id_type_list_price = lp.id_list_price
                        WHERE lp.id_product = p.code
                        AND c.code = '%s'
                      ) AS price_product
                      FROM tbl_product p
                      INNER JOIN tbl_family f ON f.code = p.id_family
                      WHERE p.active=1
                      ORDER BY p.code ;", $id_client);

    $result = $connection->execute($query)->fetchAll('assoc');
    return $result;
  }

  /* @param string $csvFile Path to the CSV file
  * @return string Delimiter
  */
  private function detectDelimiter ($csvFile) {
    $delimiters = array(
      ';' => 0,
      ',' => 0,
      "\t" => 0,
      "|" => 0
    );

    $handle = fopen($csvFile, "r");
    $firstLine = fgets($handle);
    fclose($handle);
    foreach ($delimiters as $delimiter => &$count) {
      $count = count(str_getcsv($firstLine, $delimiter));
    }

    return array_search(max($delimiters), $delimiters);
  }

  /**
  * verifyProduct Verificar si existe un producto para poder crearlo
  *
  * @param $id_product => code product to verify
  * @return array products
  */
  public function verifyProduct ($id_product) {
    // verify if exists code product
    return $this->exists(['code' => $id_product ]);
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
