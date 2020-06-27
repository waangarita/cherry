<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TblSiteConfigSlide Model
 *
 * @method \App\Model\Entity\TblSiteConfigSlide get($primaryKey, $options = [])
 * @method \App\Model\Entity\TblSiteConfigSlide newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TblSiteConfigSlide[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TblSiteConfigSlide|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TblSiteConfigSlide patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TblSiteConfigSlide[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TblSiteConfigSlide findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TblSiteConfigSlideTable extends Table
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

        $this->table('tbl_site_config_slide');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('TblSiteConfig', [
              'foreignKey' => 'id_site'
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
            ->integer('id_site')
            ->requirePresence('id_site', 'create')
            ->notEmpty('id_site');

        $validator
            ->requirePresence('img', 'create')
            ->notEmpty('img');

        $validator
            ->requirePresence('cta', 'create')
            ->notEmpty('cta');

        return $validator;
    }

    public function getSlidersBySection($section){
    $query = $this->find()
                  ->select(['id', 'img_desktop', 'img_mobile', 'cta'])
                  ->contain(['TblSiteConfig'])
                  ->where(['TblSiteConfig.slug' => $section]);
    return $query;
    }
}
