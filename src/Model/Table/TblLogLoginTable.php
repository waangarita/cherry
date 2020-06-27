<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;

/**
* TblLogLogin Model
*
* @method \App\Model\Entity\TblLogLogin get($primaryKey, $options = [])
* @method \App\Model\Entity\TblLogLogin newEntity($data = null, array $options = [])
* @method \App\Model\Entity\TblLogLogin[] newEntities(array $data, array $options = [])
* @method \App\Model\Entity\TblLogLogin|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
* @method \App\Model\Entity\TblLogLogin patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
* @method \App\Model\Entity\TblLogLogin[] patchEntities($entities, array $data, array $options = [])
* @method \App\Model\Entity\TblLogLogin findOrCreate($search, callable $callback = null, $options = [])
*
* @mixin \Cake\ORM\Behavior\TimestampBehavior
*/
class TblLogLoginTable extends Table
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

    $this->table('tbl_log_login');
    $this->displayField('id');
    $this->primaryKey('id');

    $this->addBehavior('Timestamp');
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

    return $validator;
  }

  public function getYearsOperation() {
    $connection = ConnectionManager::get('default');
    $query = "SELECT DISTINCT(YEAR(created)) AS year FROM tbl_log_login";

    try {
      $result = $connection->execute($query)->fetchAll('assoc');
      return $result;
    } catch (Exception $e) {
      $this->log($e->getMessage(), "error");
      return false;
    }
  }

  public function getUserActive ($month=0, $year=0) {
    $connection = ConnectionManager::get('default');
    $query = "SELECT count(DISTINCT(l.id_user)) AS cuantos
              FROM tbl_log_login l
              INNER JOIN adm_user u ON u.id = l.id_user
              WHERE l.id_user <> 1
              AND u.role_id <> 4 ";


    if($month <> 0) {
        $query .= sprintf('AND month(l.created) = %s ', $month);
    }

    if($year <> 0) {
        $query .= sprintf('AND year(l.created) = %s ', $year);
    }

    try {
      $result = $connection->execute($query)->fetchAll('assoc');
      return $result;
    } catch (Exception $e) {
      $this->log($e->getMessage(), "error");
      return false;
    }
  }
}
