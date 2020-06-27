<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
* TblFamily Model
*
* @method \App\Model\Entity\TblFamily get($primaryKey, $options = [])
* @method \App\Model\Entity\TblFamily newEntity($data = null, array $options = [])
* @method \App\Model\Entity\TblFamily[] newEntities(array $data, array $options = [])
* @method \App\Model\Entity\TblFamily|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
* @method \App\Model\Entity\TblFamily patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
* @method \App\Model\Entity\TblFamily[] patchEntities($entities, array $data, array $options = [])
* @method \App\Model\Entity\TblFamily findOrCreate($search, callable $callback = null, $options = [])
*
* @mixin \Cake\ORM\Behavior\TimestampBehavior
*/
class TblFamilyTable extends Table
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

    $this->table('tbl_family');
    $this->displayField('name');
    $this->primaryKey('code');

    $this->addBehavior('Timestamp');

    $this->belongsTo('TblBrand', [
      'foreignKey' => 'id_brand'
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
    ->allowEmpty('code', 'create');

    $validator
    ->requirePresence('name', 'create')
    ->notEmpty('name');

    $validator
    ->requirePresence('id_brand', 'create')
    ->notEmpty('id_brand');

    return $validator;
  }

  public function buildRules(RulesChecker $rules)
  {
      $rules->add($rules->isUnique(['code']));
      $rules->add($rules->existsIn(['id_brand'], 'TblBrand'));

      return $rules;
  }

  public function verifyFamily ($id_family) {
    return $this->exists(['code' => $id_family ]);
  }
  
  public function verifyFamilyName ($name) {
    return $this->exists(['name' => $name ]);
  }

}
