<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SdCases Model
 *
 * @property \App\Model\Table\SdProductWorkflowsTable|\Cake\ORM\Association\BelongsTo $SdProductWorkflows
 * @property \App\Model\Table\SdWorkflowActivitiesTable|\Cake\ORM\Association\BelongsTo $SdWorkflowActivities
 * @property \App\Model\Table\SdUsersTable|\Cake\ORM\Association\BelongsTo $SdUsers
 * @property |\Cake\ORM\Association\HasMany $SdCaseDistributions
 * @property |\Cake\ORM\Association\HasMany $SdCaseHistories
 * @property |\Cake\ORM\Association\HasMany $SdDocuments
 * @property \App\Model\Table\SdFieldValuesTable|\Cake\ORM\Association\HasMany $SdFieldValues
 *
 * @method \App\Model\Entity\SdCase get($primaryKey, $options = [])
 * @method \App\Model\Entity\SdCase newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SdCase[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SdCase|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdCase|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdCase patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SdCase[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SdCase findOrCreate($search, callable $callback = null, $options = [])
 */
class SdCasesTable extends Table
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

        $this->setTable('sd_cases');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('SdProductWorkflows', [
            'foreignKey' => 'sd_product_workflow_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('SdWorkflowActivities', [
            'foreignKey' => 'sd_workflow_activity_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('SdUsers', [
            'foreignKey' => 'sd_user_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('SdCaseDistributions', [
            'foreignKey' => 'sd_case_id'
        ]);
        $this->hasMany('SdCaseHistories', [
            'foreignKey' => 'sd_case_id'
        ]);
        $this->hasMany('SdDocuments', [
            'foreignKey' => 'sd_case_id'
        ]);
        $this->hasMany('SdFieldValues', [
            'foreignKey' => 'sd_case_id'
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
            ->integer('version_no')
            ->requirePresence('version_no', 'create')
            ->notEmpty('version_no');

        $validator
            ->scalar('caseNo')
            ->maxLength('caseNo', 22)
            ->requirePresence('caseNo', 'create')
            ->notEmpty('caseNo');

        $validator
            ->integer('status')
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        $validator
            ->integer('case_type')
            ->allowEmpty('case_type');

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
        $rules->add($rules->existsIn(['sd_product_workflow_id'], 'SdProductWorkflows'));
        // $rules->add($rules->existsIn(['sd_workflow_activity_id'], 'SdWorkflowActivities'));
        // $rules->add($rules->existsIn(['sd_user_id'], 'SdUsers'));

        return $rules;
    }
}
