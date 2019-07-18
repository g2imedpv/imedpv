<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SdCaseDistributions Model
 *
 * @property \App\Model\Table\SdCasesTable|\Cake\ORM\Association\BelongsTo $SdCases
 * @property \App\Model\Table\SdWorkflowActivitiesTable|\Cake\ORM\Association\BelongsTo $SdWorkflowActivities
 * @property \App\Model\Table\SdAssessmentDistributionLinksTable|\Cake\ORM\Association\BelongsTo $SdAssessmentDistributionLinks
 * @property \App\Model\Table\SdUsersTable|\Cake\ORM\Association\BelongsTo $SdUsers
 * @property \App\Model\Table\SdFieldValuesTable|\Cake\ORM\Association\HasMany $SdFieldValues
 * @property \App\Model\Table\SdFieldValues05212019Table|\Cake\ORM\Association\HasMany $SdFieldValues05212019
 * @property \App\Model\Table\SdFieldValues20190516Table|\Cake\ORM\Association\HasMany $SdFieldValues20190516
 *
 * @method \App\Model\Entity\SdCaseDistribution get($primaryKey, $options = [])
 * @method \App\Model\Entity\SdCaseDistribution newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SdCaseDistribution[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SdCaseDistribution|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdCaseDistribution|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdCaseDistribution patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SdCaseDistribution[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SdCaseDistribution findOrCreate($search, callable $callback = null, $options = [])
 */
class SdCaseDistributionsTable extends Table
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

        $this->setTable('sd_case_distributions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('SdCases', [
            'foreignKey' => 'sd_case_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('SdWorkflowActivities', [
            'foreignKey' => 'sd_workflow_activity_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('SdAssessmentDistributionLinks', [
            'foreignKey' => 'sd_assessment_distribution_link_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('SdUsers', [
            'foreignKey' => 'sd_user_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('SdFieldValues', [
            'foreignKey' => 'sd_case_distribution_id'
        ]);
        $this->hasMany('SdFieldValues05212019', [
            'foreignKey' => 'sd_case_distribution_id'
        ]);
        $this->hasMany('SdFieldValues20190516', [
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
        $rules->add($rules->existsIn(['sd_workflow_activity_id'], 'SdWorkflowActivities'));
        $rules->add($rules->existsIn(['sd_assessment_distribution_link_id'], 'SdAssessmentDistributionLinks'));
        $rules->add($rules->existsIn(['sd_user_id'], 'SdUsers'));

        return $rules;
    }
}
