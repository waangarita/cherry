<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Log\Log;

/**
* TblFileUpload Model
*
* @method \App\Model\Entity\TblFileUpload get($primaryKey, $options = [])
* @method \App\Model\Entity\TblFileUpload newEntity($data = null, array $options = [])
* @method \App\Model\Entity\TblFileUpload[] newEntities(array $data, array $options = [])
* @method \App\Model\Entity\TblFileUpload|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
* @method \App\Model\Entity\TblFileUpload patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
* @method \App\Model\Entity\TblFileUpload[] patchEntities($entities, array $data, array $options = [])
* @method \App\Model\Entity\TblFileUpload findOrCreate($search, callable $callback = null, $options = [])
*
* @mixin \Cake\ORM\Behavior\TimestampBehavior
*/
class TblFileUploadTable extends Table
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

    $this->table('tbl_file_upload');
    $this->displayField('name');
    $this->primaryKey('hash');

    $this->addBehavior('Timestamp');
    $this->hasMany('TblReport', ['className' => 'TblReport','foreignKey' => 'hashFile', 'propertyName' => 'Resumen']);
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
    ->integer('records')
    ->requirePresence('records', 'create')
    ->notEmpty('records');


    $validator
    ->requirePresence('type', 'create')
    ->notEmpty('type');

    return $validator;
  }

  public function getReport ($hash) {
    $query = $this->find()
                  ->contain(['TblReport' => function ($q) {
                    return $q->select([
                      'cuantos' => $q->func()->count('motivo'),
                      'motivo',
                      'hashFile'
                    ])->group('motivo');
                  }])
                  ->where(['hash' => $hash]);
  $this->log($query,"debug");

  return $query;
  }

private function log($str, $level){
  Log::write($level, $str, get_class($this));
}
}
