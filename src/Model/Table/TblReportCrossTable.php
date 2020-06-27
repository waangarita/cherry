<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
* TblReportCrosss Model
*
* @method \App\Model\Entity\TblReportCross get($primaryKey, $options = [])
* @method \App\Model\Entity\TblReportCross newEntity($data = null, array $options = [])
* @method \App\Model\Entity\TblReportCross[] newEntities(array $data, array $options = [])
* @method \App\Model\Entity\TblReportCross|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
* @method \App\Model\Entity\TblReportCross patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
* @method \App\Model\Entity\TblReportCross[] patchEntities($entities, array $data, array $options = [])
* @method \App\Model\Entity\TblReportCross findOrCreate($search, callable $callback = null, $options = [])
*
* @mixin \Cake\ORM\Behavior\TimestampBehavior
*/
class TblReportCrossTable extends Table
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

    $this->table('tbl_report_cross');
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


    return $validator;
  }
}
