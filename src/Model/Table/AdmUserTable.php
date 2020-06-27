<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Log\Log;
use Cake\Datasource\ConnectionManager;

/**
* AdmUser Model
*
* @property \Cake\ORM\Association\BelongsTo $AdmRole
*
* @method \App\Model\Entity\AdmUser get($primaryKey, $options = [])
* @method \App\Model\Entity\AdmUser newEntity($data = null, array $options = [])
* @method \App\Model\Entity\AdmUser[] newEntities(array $data, array $options = [])
* @method \App\Model\Entity\AdmUser|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
* @method \App\Model\Entity\AdmUser patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
* @method \App\Model\Entity\AdmUser[] patchEntities($entities, array $data, array $options = [])
* @method \App\Model\Entity\AdmUser findOrCreate($search, callable $callback = null)
*
* @mixin \Cake\ORM\Behavior\TimestampBehavior
*/
class AdmUserTable extends Table
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

    $this->table('adm_user');
    $this->displayField('name');
    $this->primaryKey('id');

    $this->addBehavior('Timestamp');

    $this->belongsTo('AdmRole', [
      'foreignKey' => 'role_id'
    ]);

    $this->belongsTo('TblClient', [
      'foreignKey' => 'id_client'
    ]);
 
    $this->belongsTo('TblCountry', [
      'foreignKey' => 'country_id'
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
    ->integer('id')
    ->allowEmpty('id', 'create');

    $validator
    ->email('email')
    ->requirePresence('email', 'create')
    ->notEmpty('email');

    $validator
    ->requirePresence('password', 'create')
    ->notEmpty('password');

    $validator
    ->requirePresence('first_name', 'create')
    ->notEmpty('first_name');

    $validator
    ->requirePresence('last_name', 'create')
    ->notEmpty('last_name');

    $validator
    ->requirePresence('phone1', 'create')
    ->notEmpty('phone1');

    $validator
    ->requirePresence('appoinment', 'create')
    ->notEmpty('appoinment');

    $validator
    ->requirePresence('id_client', 'create')
    ->notEmpty('id_client');

    $validator
    ->requirePresence('country_id', 'create')
    ->notEmpty('country_id');

    $validator
    ->requirePresence('status', 'create')
    ->notEmpty('status');

    $validator
    ->dateTime('last_login')
    ->requirePresence('last_login', 'create')
    ->notEmpty('last_login');

    return $validator;
  }

  /**
  * Returns a rules checker object that will be used for validating
  * application integrity.
  *
  * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
  * @return \Cake\ORM\RulesChecker
  */
  public function buildRules(RulesChecker $rules)
  {
    $rules->add($rules->isUnique(['email']));
    $rules->add($rules->existsIn(['role_id'], 'AdmRole'));

    return $rules;
  }

  public function findRegularUsers(){
    return $this->find()->contain(['AdmRole'])->contain(['TblClient'])->where(['AdmRole.name !=' => 'superadmin', 'AdmUser.status != ' => 'DELETED']);
  }

  /**
  * generateNewPassword Genera una nueva password de 10 digitos Mayusculas y minusculas, y es cambiada al usuario pasada por parametro
  * @param $userId. usuario al cual se le va ha cambias la password
  * @return password generada
  */
  public function generateNewPassword ($userId) {

    //Se define una cadena de caractares.
    $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    //Obtenemos la longitud de la cadena de caracteres
    $longitudCadena=strlen($cadena);

    //Se define la variable que va a contener la contraseña
    $pass = '';
    //Se define la longitud de la contraseña, en mi caso 10.
    $longitudPass=10;

    //Creamos la contraseña
    for ($i=1 ; $i<=$longitudPass ; $i++) {
      //Definimos numero aleatorio entre 0 y la longitud de la cadena de caracteres-1
      $pos=rand(0,$longitudCadena-1);

      //Vamos formando la contraseña en cada iteraccion del bucle, añadiendo a la cadena $pass la letra correspondiente a la posicion $pos en la cadena de caracteres definida.
      $pass .= substr($cadena,$pos,1);
    }

    // save new password to the user and return password generated
    $user = $this->get($userId);
    $user->password = $pass;

    if ($this->save($user)) {
      return $pass;
    }
  }

  /**
  * Log info
  * @param $str. The info to log
  * @param $level. The logging level
  */
  private function log($str, $level)
  {
    Log::write($level, $str);
  }

  public function getUserRegister ($month=0, $year=0) {
    $connection = ConnectionManager::get('default');
    $query = "SELECT count(*) cuantos
              FROM adm_user
              WHERE (role_id <> 4 AND role_id <> 1)
              AND status = 'ACTIVE' ";

    if($month <> 0) {
      $query .= sprintf('AND month(created) = %s ', $month);
    }

    if($year <> 0) {
      $query .= sprintf('AND year(created) = %s ', $year);
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
