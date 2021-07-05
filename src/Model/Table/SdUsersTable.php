<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SdUsers Model
 *
 * @property \App\Model\Table\SdRolesTable|\Cake\ORM\Association\BelongsTo $SdRoles
 * @property \App\Model\Table\SdCompaniesTable|\Cake\ORM\Association\BelongsTo $SdCompanies
 * @property \App\Model\Table\SdActivityLogsTable|\Cake\ORM\Association\HasMany $SdActivityLogs
 * @property |\Cake\ORM\Association\HasMany $SdCaseDistributions
 * @property |\Cake\ORM\Association\HasMany $SdCaseHistories
 * @property \App\Model\Table\SdCasesTable|\Cake\ORM\Association\HasMany $SdCases
 * @property |\Cake\ORM\Association\HasMany $SdCases20190516
 * @property |\Cake\ORM\Association\HasMany $SdInputHistories
 * @property \App\Model\Table\SdProductWorkflowsTable|\Cake\ORM\Association\HasMany $SdProductWorkflows
 * @property \App\Model\Table\SdUserAssignmentsTable|\Cake\ORM\Association\HasMany $SdUserAssignments
 * @property |\Cake\ORM\Association\HasMany $SdUserPreferrences
 *
 * @method \App\Model\Entity\SdUser get($primaryKey, $options = [])
 * @method \App\Model\Entity\SdUser newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SdUser[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SdUser|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdUser|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdUser patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SdUser[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SdUser findOrCreate($search, callable $callback = null, $options = [])
 */
class SdUsersTable extends Table
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

        $this->setTable('sd_users');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->belongsTo('SdRoles', [
            'foreignKey' => 'sd_role_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('SdCompanies', [
            'foreignKey' => 'sd_company_id'
        ]);
        $this->hasMany('SdActivityLogs', [
            'foreignKey' => 'sd_user_id'
        ]);
        $this->hasMany('SdCaseDistributions', [
            'foreignKey' => 'sd_user_id'
        ]);
        $this->hasMany('SdCaseHistories', [
            'foreignKey' => 'sd_user_id'
        ]);
        $this->hasMany('SdCases', [
            'foreignKey' => 'sd_user_id'
        ]);
        $this->hasMany('SdCases20190516', [
            'foreignKey' => 'sd_user_id'
        ]);
        $this->hasMany('SdInputHistories', [
            'foreignKey' => 'sd_user_id'
        ]);
        $this->hasMany('SdProductWorkflows', [
            'foreignKey' => 'sd_user_id'
        ]);
        $this->hasMany('SdUserAssignments', [
            'foreignKey' => 'sd_user_id'
        ]);
        $this->hasMany('SdUserPreferrences', [
            'foreignKey' => 'sd_user_id'
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
            ->scalar('firstname')
            ->maxLength('firstname', 50)
            ->allowEmpty('firstname');

        $validator
            ->scalar('lastname')
            ->maxLength('lastname', 50)
            ->allowEmpty('lastname');

        $validator
            ->scalar('username')
            ->maxLength('username', 60)
            ->allowEmpty('username');

        $validator
            ->email('email')
            ->allowEmpty('email');

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->allowEmpty('password');

        $validator
            ->scalar('thumbnail')
            ->maxLength('thumbnail', 255)
            ->allowEmpty('thumbnail');

        $validator
            ->scalar('site_number')
            ->maxLength('site_number', 255)
            ->allowEmpty('site_number');

        $validator
            ->scalar('site_name')
            ->maxLength('site_name', 255)
            ->allowEmpty('site_name');

        $validator
            ->scalar('title')
            ->maxLength('title', 25)
            ->allowEmpty('title');

        $validator
            ->scalar('phone_country_code')
            ->maxLength('phone_country_code', 5)
            ->allowEmpty('phone_country_code');

        $validator
            ->scalar('phone')
            ->maxLength('phone', 20)
            ->allowEmpty('phone');

        $validator
            ->scalar('extention')
            ->maxLength('extention', 5)
            ->allowEmpty('extention');

        $validator
            ->scalar('cell_country_code')
            ->maxLength('cell_country_code', 5)
            ->allowEmpty('cell_country_code');

        $validator
            ->scalar('cell_phone_no')
            ->maxLength('cell_phone_no', 20)
            ->allowEmpty('cell_phone_no');

        $validator
            ->scalar('verification')
            ->maxLength('verification', 255)
            ->allowEmpty('verification');

        $validator
            ->integer('phone_alert')
            ->allowEmpty('phone_alert');

        $validator
            ->integer('email_alert')
            ->allowEmpty('email_alert');

        $validator
            ->allowEmpty('is_never');

        $validator
            ->date('account_expire_date')
            ->allowEmpty('account_expire_date');

        $validator
            ->boolean('is_email_verified')
            ->allowEmpty('is_email_verified');

        $validator
            ->dateTime('reset_password_expire_time')
            ->allowEmpty('reset_password_expire_time');

        $validator
            ->boolean('is_import_user')
            ->allowEmpty('is_import_user');

        $validator
            ->allowEmpty('is_medra');

        $validator
            ->allowEmpty('is_whodra');

        $validator
            ->scalar('job_title')
            ->maxLength('job_title', 100)
            ->allowEmpty('job_title');

        $validator
            ->integer('assign_protocol')
            ->allowEmpty('assign_protocol');

        $validator
            ->integer('status')
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        $validator
            ->scalar('default_language')
            ->maxLength('default_language', 10)
            ->allowEmpty('default_language');

        $validator
            ->integer('created_by')
            ->allowEmpty('created_by');

        $validator
            ->dateTime('created_dt')
            ->allowEmpty('created_dt');

        $validator
            ->integer('modified_by')
            ->allowEmpty('modified_by');

        $validator
            ->dateTime('modified_dt')
            ->allowEmpty('modified_dt');

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
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->existsIn(['sd_role_id'], 'SdRoles'));
        $rules->add($rules->existsIn(['sd_company_id'], 'SdCompanies'));

        return $rules;
    }
}
