<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Log\Log;
use Cake\Datasource\ConnectionManager;

/**
 * TblClient Model
 *
 * @method \App\Model\Entity\TblClient get($primaryKey, $options = [])
 * @method \App\Model\Entity\TblClient newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TblClient[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TblClient|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TblClient patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TblClient[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TblClient findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TblClientTable extends Table
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

        $this->table('tbl_client');
        $this->displayField('name');
        $this->primaryKey('code');

        $this->addBehavior('Timestamp');
        $this->belongsTo('TblListPrice', [
              'foreignKey' => 'id_type_list_price'
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
            ->requirePresence('id_type_list_price', 'create')
            ->notEmpty('id_type_list_price');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        return $validator;
    }


    /**
     * Busca si existe una lista de precios especifica
     *
     * @param $listPrice , identificador de la lista precios Ejemplo : Standard:
     * @return True || False
     */
    public function searchClient ($listPrice) {
      return $this->exists(['id_type_list_price' => $listPrice ]);
    }

    /**
     * Buscamos si existe una compania/cliente/empresa por el nombre que nos pasen por parametro
     *
     * @param $company , nombre compania
     * @return codigo de la compania
     */
    public function getCompany($company) {
      $connection = ConnectionManager::get('default');
      $nameCompany = '%'.strtoupper($company).'%';
      $sqlCompany = sprintf("SELECT code FROM tbl_client WHERE upper(name) LIKE upper('%s') ;", $nameCompany);
      $result = $connection->execute($sqlCompany)->fetchAll('assoc');
      if(empty($result)) {
        $code = '1';
      } else {
        $code = $result[0]['code'];
      }
      return $code;
    }
}
