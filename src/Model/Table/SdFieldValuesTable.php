<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SdFieldValues Model
 *
 * @property \App\Model\Table\SdCasesTable|\Cake\ORM\Association\BelongsTo $SdCases
 * @property \App\Model\Table\SdFieldsTable|\Cake\ORM\Association\BelongsTo $SdFields
 * @property \App\Model\Table\SdCaseDistributionsTable|\Cake\ORM\Association\BelongsTo $SdCaseDistributions
 * @property \App\Model\Table\SdSectionSetsTable|\Cake\ORM\Association\HasMany $SdSectionSets
 *
 * @method \App\Model\Entity\SdFieldValue get($primaryKey, $options = [])
 * @method \App\Model\Entity\SdFieldValue newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SdFieldValue[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SdFieldValue|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdFieldValue|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdFieldValue patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SdFieldValue[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SdFieldValue findOrCreate($search, callable $callback = null, $options = [])
 */
class SdFieldValuesTable extends Table
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

        $this->setTable('sd_field_values');
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
        $this->hasMany('SdSectionSets', [
            'foreignKey' => 'sd_field_value_id'
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
        ->allowEmpty('field_value');

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
