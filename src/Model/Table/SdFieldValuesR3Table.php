<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SdFieldValuesR3 Model
 *
 * @property \App\Model\Table\SdCasesTable|\Cake\ORM\Association\BelongsTo $SdCases
 * @property \App\Model\Table\SdFieldsTable|\Cake\ORM\Association\BelongsTo $SdFields
 * @property \App\Model\Table\SdCaseDistributionsTable|\Cake\ORM\Association\BelongsTo $SdCaseDistributions
 *
 * @method \App\Model\Entity\SdFieldValuesR3 get($primaryKey, $options = [])
 * @method \App\Model\Entity\SdFieldValuesR3 newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SdFieldValuesR3[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SdFieldValuesR3|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdFieldValuesR3|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdFieldValuesR3 patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SdFieldValuesR3[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SdFieldValuesR3 findOrCreate($search, callable $callback = null, $options = [])
 */
class SdFieldValuesR3Table extends Table
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

        $this->setTable('sd_field_values_r3');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('SdCases', [
            'foreignKey' => 'sd_case_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('SdFields', [
            'foreignKey' => 'sd_field_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('SdCaseDistributions', [
            'foreignKey' => 'sd_case_distribution_id'
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
            ->scalar('set_number')
            ->maxLength('set_number', 10)
            ->requirePresence('set_number', 'create')
            ->notEmpty('set_number');

        $validator
            ->scalar('field_value')
            ->requirePresence('field_value', 'create')
            ->notEmpty('field_value');

        $validator
            ->dateTime('created_time')
            ->requirePresence('created_time', 'create')
            ->notEmpty('created_time');

        $validator
            ->boolean('status')
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
        $rules->add($rules->existsIn(['sd_case_id'], 'SdCases'));
        $rules->add($rules->existsIn(['sd_field_id'], 'SdFields'));
        $rules->add($rules->existsIn(['sd_case_distribution_id'], 'SdCaseDistributions'));

        return $rules;
    }
}
