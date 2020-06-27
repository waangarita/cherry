<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TblSiteConfig Model
 *
 * @method \App\Model\Entity\TblSiteConfig get($primaryKey, $options = [])
 * @method \App\Model\Entity\TblSiteConfig newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TblSiteConfig[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TblSiteConfig|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TblSiteConfig patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TblSiteConfig[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TblSiteConfig findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TblSiteConfigTable extends Table
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

        $this->table('tbl_site_config');
        $this->displayField('name');
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('slug', 'create')
            ->notEmpty('slug');

        $validator
            ->requirePresence('cta1', 'create')
            ->notEmpty('cta1');

        $validator
            ->requirePresence('slide1', 'create')
            ->notEmpty('slide1');

        $validator
            ->requirePresence('slide_mobile1', 'create')
            ->notEmpty('slide_mobile1');

        $validator
            ->requirePresence('cta2', 'create')
            ->notEmpty('cta2');

        $validator
            ->requirePresence('slide2', 'create')
            ->notEmpty('slide2');

        $validator
            ->requirePresence('slide_mobile2', 'create')
            ->notEmpty('slide_mobile2');

        $validator
            ->requirePresence('cta3', 'create')
            ->notEmpty('cta3');

        $validator
            ->requirePresence('slide3', 'create')
            ->notEmpty('slide3');

        $validator
            ->allowEmpty('body');

        $validator
            ->requirePresence('slide_mobile3', 'create')
            ->notEmpty('slide_mobile3');

        $validator
            ->requirePresence('banner', 'create')
            ->notEmpty('banner');

        $validator
            ->requirePresence('banner_mobile', 'create')
            ->notEmpty('banner_mobile');

        $validator
            ->requirePresence('cta_banner', 'create')
            ->notEmpty('cta_banner');

        return $validator;
    }

    public function getPageBySection($slug) {
      if ($slug == 'index') {
        $query = $this->find()
                      ->select(['cta1', 'slide1', 'slide_mobile1', 'cta2', 'slide2', 'slide_mobile2', 'cta3', 'slide3', 'slide_mobile3', 'banner', 'banner_mobile', 'cta_banner'])
                      ->where(['slug' => $slug]);
      }

      if ($slug == 'terminos-condiciones') {
        $query = $this->find()
                      ->select(['body'])
                      ->where(['slug' => $slug])->toArray();
      }

      return $query;
    }
}
