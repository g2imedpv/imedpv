<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SdSenders Model
 *
 * @property \App\Model\Table\SdCompaniesTable|\Cake\ORM\Association\BelongsTo $SdCompanies
 *
 * @method \App\Model\Entity\SdSender get($primaryKey, $options = [])
 * @method \App\Model\Entity\SdSender newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SdSender[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SdSender|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdSender|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdSender patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SdSender[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SdSender findOrCreate($search, callable $callback = null, $options = [])
 */
class SdSendersTable extends Table
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

        $this->setTable('sd_senders');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('SdCompanies', [
            'foreignKey' => 'sd_company_id',
            'joinType' => 'INNER'
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
            ->integer('type')
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        $validator
            ->scalar('organisation')
            ->maxLength('organisation', 100)
            ->allowEmpty('organisation');

        $validator
            ->scalar('department')
            ->maxLength('department', 60)
            ->allowEmpty('department');

        $validator
            ->scalar('title')
            ->maxLength('title', 50)
            ->allowEmpty('title');

        $validator
            ->scalar('given_name')
            ->maxLength('given_name', 60)
            ->allowEmpty('given_name');

        $validator
            ->scalar('middle_name')
            ->maxLength('middle_name', 60)
            ->allowEmpty('middle_name');

        $validator
            ->scalar('family_name')
            ->maxLength('family_name', 60)
            ->allowEmpty('family_name');

        $validator
            ->scalar('street')
            ->maxLength('street', 100)
            ->allowEmpty('street');

        $validator
            ->scalar('city')
            ->maxLength('city', 35)
            ->allowEmpty('city');

        $validator
            ->scalar('state')
            ->maxLength('state', 40)
            ->allowEmpty('state');

        $validator
            ->scalar('postcode')
            ->maxLength('postcode', 15)
            ->allowEmpty('postcode');

        $validator
            ->scalar('country')
            ->maxLength('country', 2)
            ->allowEmpty('country');

        $validator
            ->scalar('telephone')
            ->maxLength('telephone', 33)
            ->allowEmpty('telephone');

        $validator
            ->scalar('fax')
            ->maxLength('fax', 33)
            ->allowEmpty('fax');

        $validator
            ->email('email')
            ->allowEmpty('email');

        $validator
            ->scalar('cn_mark')
            ->maxLength('cn_mark', 100)
            ->allowEmpty('cn_mark');

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
        $rules->add($rules->existsIn(['sd_company_id'], 'SdCompanies'));

        return $rules;
    }
}
