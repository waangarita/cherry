<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;

/**
* TblListPrice Model
*
* @method \App\Model\Entity\TblListPrice get($primaryKey, $options = [])
* @method \App\Model\Entity\TblListPrice newEntity($data = null, array $options = [])
* @method \App\Model\Entity\TblListPrice[] newEntities(array $data, array $options = [])
* @method \App\Model\Entity\TblListPrice|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
* @method \App\Model\Entity\TblListPrice patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
* @method \App\Model\Entity\TblListPrice[] patchEntities($entities, array $data, array $options = [])
* @method \App\Model\Entity\TblListPrice findOrCreate($search, callable $callback = null, $options = [])
*
* @mixin \Cake\ORM\Behavior\TimestampBehavior
*/
class TblListPriceTable extends Table
{

  /**
  * Initialize method
  *
  * @param array $config The configuration for the Table.
  * @return void
  */
  public function initialize(array $config) {
    parent::initialize($config);

    $this->table('tbl_list_price');
    $this->displayField('name');
    $this->primaryKey('name');

    $this->addBehavior('Timestamp');

    $this->hasMany('TblDetailListPrice', ['className' => 'TblDetailListPrice','foreignKey' => 'id_list_price', 'propertyName' => 'detailList']);
  }

  /**
  * Default validation rules.
  *
  * @param \Cake\Validation\Validator $validator Validator instance.
  * @return \Cake\Validation\Validator
  */
  public function validationDefault(Validator $validator) {
    $validator
    ->allowEmpty('name', 'create');

    return $validator;
  }

  public function loadDataLocalInfile ($file, $hash) {

    ini_set('memory_limit', '1024M');
    ini_set('auto_detect_line_endings', true);
    $connection = ConnectionManager::get('default');
    $fieldsTerminatedIn = $this->detectDelimiter($file);
    $linesTerminatedIn = $fieldsTerminatedIn == ',' ? '\r' : '\n';
    $query = sprintf("LOAD DATA LOCAL INFILE '%s'
                      IGNORE
                      INTO TABLE tbl_detail_list_price_tmp
                      FIELDS TERMINATED BY '%s' ENCLOSED BY ''
                      LINES TERMINATED BY '%s'
                      IGNORE 1 LINES
                      (id_product, id_list_price, price);",$file, $fieldsTerminatedIn, $linesTerminatedIn);

    
    $query2 = sprintf("INSERT INTO tbl_file_upload (name, type, records, records_updated, hash)
                      VALUES ('%s', 'LIST_PRICE', (SELECT count(*) FROM tbl_detail_list_price_tmp), (SELECT count(*) FROM tbl_detail_list_price),'%s');", $file, $hash);
    
    $query3 = sprintf("CALL uploadListPrice('%s');", $hash);

    $query4="TRUNCATE TABLE tbl_detail_list_price_tmp;";

    try {
      $connection->begin();
      $connection->execute($query);
      $connection->execute($query2);
      $connection->execute($query3);
      $connection->execute($query4);
      $connection->commit();
      return true;
    } catch (\Exception $e) {
      $connection->rollback();
      $connection->disconnect();
      $this->log($e->getMessage(), "error");
      return false;
    }
  }

  /* @param string $csvFile Path to the CSV file
  * @return string Delimiter
  */
  private function detectDelimiter($csvFile) {
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
  * Log info
  * @param $str. The info to log
  * @param $level. The logging level
  */
  private function log($str, $level){
    Log::write($level, $str, get_class($this));
  }

  /**
  * countReport cuanta cuantos reportes.
  * @return cantidad de reportes
  */
  public function countReport () {
    $connection = ConnectionManager::get('default');
    $query = sprintf("SELECT count(motivo) AS cuantos FROM tbl_report");
    $report = $connection->execute($query)->fetchAll('assoc');
    return $report;
  }


}
?>
