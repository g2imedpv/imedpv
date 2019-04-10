<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SdAccessmentDistributionLinks Model
 *
 * @property \App\Model\Table\SdProductWorkflowsTable|\Cake\ORM\Association\BelongsTo $SdProductWorkflows
 *
 * @method \App\Model\Entity\SdAccessmentDistributionLink get($primaryKey, $options = [])
 * @method \App\Model\Entity\SdAccessmentDistributionLink newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SdAccessmentDistributionLink[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SdAccessmentDistributionLink|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdAccessmentDistributionLink|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdAccessmentDistributionLink patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SdAccessmentDistributionLink[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SdAccessmentDistributionLink findOrCreate($search, callable $callback = null, $options = [])
 */
class SdAccessmentDistributionLinksTable extends Table
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

        $this->setTable('sd_accessment_distribution_links');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('SdProductWorkflows', [
            'foreignKey' => 'sd_product_workflow_id',
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
            ->integer('distribution')
            ->requirePresence('distribution', 'create')
            ->notEmpty('distribution');

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

        return $rules;
    }
}
