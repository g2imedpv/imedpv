<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SdWorkflowActivities Model
 *
 * @property \App\Model\Table\SdWorkflowsTable|\Cake\ORM\Association\BelongsTo $SdWorkflows
 * @property \App\Model\Table\SdActivitySectionPermissionsTable|\Cake\ORM\Association\HasMany $SdActivitySectionPermissions
 * @property |\Cake\ORM\Association\HasMany $SdCaseDistributions
 * @property |\Cake\ORM\Association\HasMany $SdCaseHistories
 * @property \App\Model\Table\SdCasesTable|\Cake\ORM\Association\HasMany $SdCases
 * @property |\Cake\ORM\Association\HasMany $SdCases20190516
 * @property |\Cake\ORM\Association\HasMany $SdInputHistories
 * @property \App\Model\Table\SdUserAssignmentsTable|\Cake\ORM\Association\HasMany $SdUserAssignments
 *
 * @method \App\Model\Entity\SdWorkflowActivity get($primaryKey, $options = [])
 * @method \App\Model\Entity\SdWorkflowActivity newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SdWorkflowActivity[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SdWorkflowActivity|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdWorkflowActivity|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdWorkflowActivity patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SdWorkflowActivity[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SdWorkflowActivity findOrCreate($search, callable $callback = null, $options = [])
 */
class SdWorkflowActivitiesTable extends Table
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

        $this->setTable('sd_workflow_activities');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('SdWorkflows', [
            'foreignKey' => 'sd_workflow_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('SdActivitySectionPermissions', [
            'foreignKey' => 'sd_workflow_activity_id'
        ]);
        $this->hasMany('SdCaseDistributions', [
            'foreignKey' => 'sd_workflow_activity_id'
        ]);
        $this->hasMany('SdCaseHistories', [
            'foreignKey' => 'sd_workflow_activity_id'
        ]);
        $this->hasMany('SdCases', [
            'foreignKey' => 'sd_workflow_activity_id'
        ]);
        $this->hasMany('SdCases20190516', [
            'foreignKey' => 'sd_workflow_activity_id'
        ]);
        $this->hasMany('SdInputHistories', [
            'foreignKey' => 'sd_workflow_activity_id'
        ]);
        $this->hasMany('SdUserAssignments', [
            'foreignKey' => 'sd_workflow_activity_id'
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
            ->integer('order_no')
            ->requirePresence('order_no', 'create')
            ->notEmpty('order_no');

        $validator
            ->integer('step_backward')
            ->requirePresence('step_backward', 'create')
            ->notEmpty('step_backward');

        $validator
            ->scalar('due_day')
            ->maxLength('due_day', 50)
            ->allowEmpty('due_day');

        $validator
            ->scalar('activity_name')
            ->requirePresence('activity_name', 'create')
            ->notEmpty('activity_name');

        $validator
            ->scalar('description')
            ->maxLength('description', 100)
            ->requirePresence('description', 'create')
            ->notEmpty('description');

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
        $rules->add($rules->existsIn(['sd_workflow_id'], 'SdWorkflows'));

        return $rules;
    }
}
