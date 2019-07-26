<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SdContacts Model
 *
 * @method \App\Model\Entity\SdContact get($primaryKey, $options = [])
 * @method \App\Model\Entity\SdContact newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SdContact[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SdContact|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdContact|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdContact patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SdContact[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SdContact findOrCreate($search, callable $callback = null, $options = [])
 */
class SdContactsTable extends Table
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

        $this->setTable('sd_contacts');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');
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
            ->scalar('contact_type')
            ->maxLength('contact_type', 40)
            ->requirePresence('contact_type', 'create')
            ->notEmpty('contact_type');

        $validator
            ->scalar('authority')
            ->maxLength('authority', 40)
            ->requirePresence('authority', 'create')
            ->notEmpty('authority');

        $validator
            ->boolean('data_privacy')
            ->requirePresence('data_privacy', 'create')
            ->notEmpty('data_privacy');

        $validator
            ->scalar('blinded_report')
            ->maxLength('blinded_report', 40)
            ->requirePresence('blinded_report', 'create')
            ->notEmpty('blinded_report');

        $validator
            ->scalar('contactId')
            ->maxLength('contactId', 11)
            ->requirePresence('contactId', 'create')
            ->notEmpty('contactId');

        $validator
            ->scalar('preferred_route')
            ->maxLength('preferred_route', 40)
            ->requirePresence('preferred_route', 'create')
            ->notEmpty('preferred_route');

        $validator
            ->scalar('format_type')
            ->maxLength('format_type', 40)
            ->requirePresence('format_type', 'create')
            ->notEmpty('format_type');

        $validator
            ->scalar('title')
            ->maxLength('title', 10)
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->scalar('given_name')
            ->maxLength('given_name', 35)
            ->requirePresence('given_name', 'create')
            ->notEmpty('given_name');

        $validator
            ->scalar('family_name')
            ->maxLength('family_name', 35)
            ->requirePresence('family_name', 'create')
            ->notEmpty('family_name');

        $validator
            ->scalar('middle_name')
            ->maxLength('middle_name', 15)
            ->requirePresence('middle_name', 'create')
            ->notEmpty('middle_name');

        $validator
            ->scalar('address')
            ->maxLength('address', 100)
            ->requirePresence('address', 'create')
            ->notEmpty('address');

        $validator
            ->scalar('address_extension')
            ->maxLength('address_extension', 100)
            ->requirePresence('address_extension', 'create')
            ->notEmpty('address_extension');

        $validator
            ->scalar('city')
            ->maxLength('city', 35)
            ->requirePresence('city', 'create')
            ->notEmpty('city');

        $validator
            ->scalar('state_province')
            ->maxLength('state_province', 40)
            ->requirePresence('state_province', 'create')
            ->notEmpty('state_province');

        $validator
            ->scalar('zipcode')
            ->maxLength('zipcode', 15)
            ->requirePresence('zipcode', 'create')
            ->notEmpty('zipcode');

        $validator
            ->scalar('country')
            ->maxLength('country', 30)
            ->requirePresence('country', 'create')
            ->notEmpty('country');

        $validator
            ->scalar('phone')
            ->maxLength('phone', 10)
            ->requirePresence('phone', 'create')
            ->notEmpty('phone');

        $validator
            ->scalar('phone_extension')
            ->maxLength('phone_extension', 10)
            ->requirePresence('phone_extension', 'create')
            ->notEmpty('phone_extension');

        $validator
            ->scalar('fax')
            ->maxLength('fax', 10)
            ->requirePresence('fax', 'create')
            ->notEmpty('fax');

        $validator
            ->scalar('fax_extension')
            ->maxLength('fax_extension', 10)
            ->requirePresence('fax_extension', 'create')
            ->notEmpty('fax_extension');

        $validator
            ->scalar('email_address')
            ->maxLength('email_address', 100)
            ->requirePresence('email_address', 'create')
            ->notEmpty('email_address');

        $validator
            ->scalar('website')
            ->maxLength('website', 100)
            ->requirePresence('website', 'create')
            ->notEmpty('website');

        return $validator;
    }
}
