<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SdInputHistories Model
 *
 * @property \App\Model\Table\SdFieldValuesTable|\Cake\ORM\Association\BelongsTo $SdFieldValues
 * @property \App\Model\Table\SdUsersTable|\Cake\ORM\Association\BelongsTo $SdUsers
 * @property |\Cake\ORM\Association\BelongsTo $SdWorkflowActivities
 *
 * @method \App\Model\Entity\SdInputHistory get($primaryKey, $options = [])
 * @method \App\Model\Entity\SdInputHistory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SdInputHistory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SdInputHistory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdInputHistory|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdInputHistory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SdInputHistory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SdInputHistory findOrCreate($search, callable $callback = null, $options = [])
 */
class SdInputHistoriesTable extends Table
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

        $this->setTable('sd_input_histories');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('SdFieldValues', [
            'foreignKey' => 'sd_field_value_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('SdUsers', [
            'foreignKey' => 'sd_user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('SdWorkflowActivities', [
            'foreignKey' => 'sd_workflow_activity_id',
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
            ->scalar('input')
            ->requirePresence('input', 'create')
            ->notEmpty('input');

        $validator
            ->dateTime('time_changed')
            ->requirePresence('time_changed', 'create')
            ->notEmpty('time_changed');

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
        $rules->add($rules->existsIn(['sd_field_value_id'], 'SdFieldValues'));
        $rules->add($rules->existsIn(['sd_user_id'], 'SdUsers'));
        $rules->add($rules->existsIn(['sd_workflow_activity_id'], 'SdWorkflowActivities'));

        return $rules;
    }
}
