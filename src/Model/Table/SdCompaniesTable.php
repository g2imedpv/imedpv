<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SdCompanies Model
 *
 * @property \App\Model\Table\SdUserTypesTable|\Cake\ORM\Association\BelongsTo $SdUserTypes
 * @property |\Cake\ORM\Association\HasMany $SdProductWorkflows
 * @property |\Cake\ORM\Association\HasMany $SdProducts
 * @property \App\Model\Table\SdUsersTable|\Cake\ORM\Association\HasMany $SdUsers
 *
 * @method \App\Model\Entity\SdCompany get($primaryKey, $options = [])
 * @method \App\Model\Entity\SdCompany newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SdCompany[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SdCompany|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdCompany|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdCompany patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SdCompany[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SdCompany findOrCreate($search, callable $callback = null, $options = [])
 */
class SdCompaniesTable extends Table
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

        $this->setTable('sd_companies');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('SdUserTypes', [
            'foreignKey' => 'sd_user_type_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('SdProductWorkflows', [
            'foreignKey' => 'sd_company_id'
        ]);
        $this->hasMany('SdProducts', [
            'foreignKey' => 'sd_company_id'
        ]);
        $this->hasMany('SdUsers', [
            'foreignKey' => 'sd_company_id'
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
            ->scalar('company_name')
            ->maxLength('company_name', 255)
            ->allowEmpty('company_name')
            ->add('company_name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('company_email')
            ->maxLength('company_email', 100)
            ->allowEmpty('company_email')
            ->add('company_email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('website')
            ->maxLength('website', 255)
            ->allowEmpty('website')
            ->add('website', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('address_line1')
            ->maxLength('address_line1', 255)
            ->allowEmpty('address_line1');

        $validator
            ->scalar('address_line2')
            ->maxLength('address_line2', 255)
            ->allowEmpty('address_line2');

        $validator
            ->scalar('zipcode')
            ->maxLength('zipcode', 20)
            ->allowEmpty('zipcode');

        $validator
            ->scalar('city')
            ->maxLength('city', 50)
            ->allowEmpty('city');

        $validator
            ->scalar('state')
            ->maxLength('state', 50)
            ->allowEmpty('state');

        $validator
            ->scalar('country')
            ->maxLength('country', 50)
            ->allowEmpty('country');

        $validator
            ->scalar('cell_country_code')
            ->maxLength('cell_country_code', 5)
            ->allowEmpty('cell_country_code');

        $validator
            ->scalar('cell_phone_no')
            ->maxLength('cell_phone_no', 20)
            ->allowEmpty('cell_phone_no');

        $validator
            ->scalar('phone1_country_code')
            ->maxLength('phone1_country_code', 5)
            ->allowEmpty('phone1_country_code');

        $validator
            ->scalar('phone1')
            ->maxLength('phone1', 20)
            ->allowEmpty('phone1')
            ->add('phone1', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('extention1')
            ->maxLength('extention1', 5)
            ->allowEmpty('extention1');

        $validator
            ->scalar('phone2_country_code')
            ->maxLength('phone2_country_code', 5)
            ->allowEmpty('phone2_country_code');

        $validator
            ->scalar('phone2')
            ->maxLength('phone2', 20)
            ->allowEmpty('phone2');

        $validator
            ->scalar('extention2')
            ->maxLength('extention2', 5)
            ->allowEmpty('extention2');

        $validator
            ->scalar('fax1_country_code')
            ->maxLength('fax1_country_code', 5)
            ->allowEmpty('fax1_country_code');

        $validator
            ->scalar('fax1')
            ->maxLength('fax1', 20)
            ->allowEmpty('fax1');

        $validator
            ->scalar('fax2_country_code')
            ->maxLength('fax2_country_code', 5)
            ->allowEmpty('fax2_country_code');

        $validator
            ->scalar('fax2')
            ->maxLength('fax2', 20)
            ->allowEmpty('fax2');

        $validator
            ->integer('transaction_currency')
            ->allowEmpty('transaction_currency');

        $validator
            ->integer('no_of_billing_cycle')
            ->allowEmpty('no_of_billing_cycle');

        $validator
            ->integer('current_billing_cycle')
            ->allowEmpty('current_billing_cycle');

        $validator
            ->allowEmpty('no_of_whodra');

        $validator
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        $validator
            ->requirePresence('un_paid', 'create')
            ->notEmpty('un_paid');

        $validator
            ->allowEmpty('is_medra');

        $validator
            ->allowEmpty('is_whodra');

        $validator
            ->integer('create_by')
            ->allowEmpty('create_by');

        $validator
            ->dateTime('created_dt')
            ->requirePresence('created_dt', 'create')
            ->notEmpty('created_dt');

        $validator
            ->integer('modify_by')
            ->allowEmpty('modify_by');

        $validator
            ->dateTime('modified_dt')
            ->requirePresence('modified_dt', 'create')
            ->notEmpty('modified_dt');

        $validator
            ->scalar('product_abbreviation')
            ->maxLength('product_abbreviation', 5)
            ->requirePresence('product_abbreviation', 'create')
            ->notEmpty('product_abbreviation');

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
        $rules->add($rules->isUnique(['company_name']));
        $rules->add($rules->isUnique(['phone1']));
        $rules->add($rules->isUnique(['company_email']));
        $rules->add($rules->isUnique(['website']));
        $rules->add($rules->existsIn(['sd_user_type_id'], 'SdUserTypes'));

        return $rules;
    }
}
