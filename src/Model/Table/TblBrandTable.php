<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
* TblBrand Model
*
* @method \App\Model\Entity\TblBrand get($primaryKey, $options = [])
* @method \App\Model\Entity\TblBrand newEntity($data = null, array $options = [])
* @method \App\Model\Entity\TblBrand[] newEntities(array $data, array $options = [])
* @method \App\Model\Entity\TblBrand|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
* @method \App\Model\Entity\TblBrand patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
* @method \App\Model\Entity\TblBrand[] patchEntities($entities, array $data, array $options = [])
* @method \App\Model\Entity\TblBrand findOrCreate($search, callable $callback = null, $options = [])
*
* @mixin \Cake\ORM\Behavior\TimestampBehavior
*/
class TblBrandTable extends Table
{

  /**
  * Initialize method
  *s
  * @param array $config The configuration for the Table.
  * @return void
  */
  public function initialize(array $config)
  {
    parent::initialize($config);

    $this->table('tbl_brand');
    $this->displayField('name');
    $this->primaryKey('code');

    $this->addBehavior('Timestamp');

    $this->hasMany('TblFamily', ['className' => 'TblFamily','foreignKey' => 'id_brand', 'propertyName' => 'Families']);
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
    ->allowEmpty('code', 'create');

    $validator
    ->requirePresence('name', 'create')
    ->notEmpty('name');

    return $validator;
  }

  public function verifyBrand ($idBrand) {
    return $this->exists(['code' => $idBrand ]);
  }

  public function buildRules(RulesChecker $rules)
  {
      $rules->add($rules->isUnique(['code']));
      return $rules;
  }

}
