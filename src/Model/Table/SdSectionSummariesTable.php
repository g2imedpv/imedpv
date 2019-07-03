<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SdSectionSummaries Model
 *
 * @property \App\Model\Table\SdSectionsTable|\Cake\ORM\Association\BelongsTo $SdSections
 *
 * @method \App\Model\Entity\SdSectionSummary get($primaryKey, $options = [])
 * @method \App\Model\Entity\SdSectionSummary newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SdSectionSummary[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SdSectionSummary|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdSectionSummary|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdSectionSummary patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SdSectionSummary[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SdSectionSummary findOrCreate($search, callable $callback = null, $options = [])
 */
class SdSectionSummariesTable extends Table
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

        $this->setTable($config['table']);
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

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
            ->scalar('fields')
            ->maxLength('fields', 100)
            ->requirePresence('fields', 'create')
            ->notEmpty('fields');

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
        $rules->add($rules->existsIn(['sd_section_id'], 'SdSections'));

        return $rules;
    }
}
