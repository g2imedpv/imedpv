<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SdActivitySectionPermissions Model
 *
 * @property \App\Model\Table\SdWorkflowActivitiesTable|\Cake\ORM\Association\BelongsTo $SdWorkflowActivities
 * @property \App\Model\Table\SdSectionsTable|\Cake\ORM\Association\BelongsTo $SdSections
 *
 * @method \App\Model\Entity\SdActivitySectionPermission get($primaryKey, $options = [])
 * @method \App\Model\Entity\SdActivitySectionPermission newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SdActivitySectionPermission[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SdActivitySectionPermission|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdActivitySectionPermission|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdActivitySectionPermission patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SdActivitySectionPermission[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SdActivitySectionPermission findOrCreate($search, callable $callback = null, $options = [])
 */
class SdActivitySectionPermissionsTable extends Table
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

        $this->setTable('sd_activity_section_permissions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('SdWorkflowActivities', [
            'foreignKey' => 'sd_workflow_activity_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('SdSections', [
            'foreignKey' => 'sd_section_id',
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
            ->integer('action')
            ->requirePresence('action', 'create')
            ->notEmpty('action');

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
        $rules->add($rules->existsIn(['sd_workflow_activity_id'], 'SdWorkflowActivities'));
        $rules->add($rules->existsIn(['sd_section_id'], 'SdSections'));

        return $rules;
    }
}
