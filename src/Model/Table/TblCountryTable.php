<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

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
class TblCountryTable extends Table
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

    $this->table('tbl_country');
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
