<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SdProducts Model
 *
 * @property \App\Model\Table\SdCompaniesTable|\Cake\ORM\Association\BelongsTo $SdCompanies
 * @property \App\Model\Table\SdProductWorkflowsTable|\Cake\ORM\Association\HasMany $SdProductWorkflows
 *
 * @method \App\Model\Entity\SdProduct get($primaryKey, $options = [])
 * @method \App\Model\Entity\SdProduct newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SdProduct[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SdProduct|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdProduct|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdProduct patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SdProduct[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SdProduct findOrCreate($search, callable $callback = null, $options = [])
 */
class SdProductsTable extends Table
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

        $this->setTable('sd_products');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('SdCompanies', [
            'foreignKey' => 'sd_company_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('SdProductWorkflows', [
            'foreignKey' => 'sd_product_id'
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
            ->scalar('product_name')
            ->maxLength('product_name', 255)
            ->requirePresence('product_name', 'create')
            ->notEmpty('product_name');

        $validator
            ->scalar('study_no')
            ->requirePresence('study_no', 'create')
            ->notEmpty('study_no');

        $validator
            ->scalar('study_name')
            ->maxLength('study_name', 100)
            ->requirePresence('study_name', 'create')
            ->notEmpty('study_name');

        $validator
            ->integer('study_type')
            ->requirePresence('study_type', 'create')
            ->notEmpty('study_type');

        $validator
            ->scalar('WHODD_decode')
            ->maxLength('WHODD_decode', 50)
            //->requirePresence('WHODD_decode', 'create')
            ->allowEmpty('WHODD_decode', 'create');

        $validator
            ->scalar('short_desc')
            ->maxLength('short_desc', 255)
            ->requirePresence('short_desc', 'create')
            ->notEmpty('short_desc');

        $validator
            ->scalar('product_desc')
            ->maxLength('product_desc', 90)
            ->requirePresence('product_desc', 'create')
            ->notEmpty('product_desc');

        $validator
            ->scalar('blinding_tech')
            ->maxLength('blinding_tech', 50)
            ->requirePresence('blinding_tech', 'create')
            ->notEmpty('blinding_tech');

        $validator
            ->integer('sd_product_flag')
            ->requirePresence('sd_product_flag', 'create')
            ->notEmpty('sd_product_flag');

        $validator
            ->scalar('product_abbreviation')
            ->maxLength('product_abbreviation', 5)
            ->requirePresence('product_abbreviation', 'create')
            ->notEmpty('product_abbreviation');

        $validator
            ->scalar('caseNo_convention')
            ->maxLength('caseNo_convention', 50)
            ->requirePresence('caseNo_convention', 'create')
            ->notEmpty('caseNo_convention');

        $validator
            ->scalar('WHODD_code')
            ->maxLength('WHODD_code', 50)
            //->requirePresence('WHODD_code', 'create')
            ->allowEmpty('WHODD_code', 'create');

        $validator
            ->scalar('WHODD_name')
            ->maxLength('WHODD_name', 100)
            //->requirePresence('WHODD_name', 'create')
            ->allowEmpty('WHODD_name', 'create');

        $validator
            ->integer('e2b_version')
            ->requirePresence('e2b_version', 'create')
            ->notEmpty('e2b_version');

        $validator
            ->scalar('mfr_name')
            ->maxLength('mfr_name', 100)
            ->requirePresence('mfr_name', 'create')
            ->notEmpty('mfr_name');

        $validator
            ->scalar('start_date')
            ->maxLength('start_date', 10)
            ->requirePresence('start_date', 'create')
            ->notEmpty('start_date');

        $validator
            ->scalar('end_date')
            ->maxLength('end_date', 10)
            ->requirePresence('end_date', 'create')
            ->notEmpty('end_date');

        $validator
            ->integer('status')
            ->requirePresence('status', 'create')
            ->notEmpty('status');

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
        $rules->add($rules->existsIn(['sd_company_id'], 'SdCompanies'));

        return $rules;
    }
}
