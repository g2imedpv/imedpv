<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SdSectionStructuresR3 Model
 *
 * @property \App\Model\Table\SdSectionsTable|\Cake\ORM\Association\BelongsTo $SdSections
 * @property \App\Model\Table\SdFieldsTable|\Cake\ORM\Association\BelongsTo $SdFields
 *
 * @method \App\Model\Entity\SdSectionStructuresR3 get($primaryKey, $options = [])
 * @method \App\Model\Entity\SdSectionStructuresR3 newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SdSectionStructuresR3[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SdSectionStructuresR3|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdSectionStructuresR3|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdSectionStructuresR3 patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SdSectionStructuresR3[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SdSectionStructuresR3 findOrCreate($search, callable $callback = null, $options = [])
 */
class SdSectionStructuresR3Table extends Table
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

        $this->setTable('sd_section_structures_r3');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('SdSections', [
            'foreignKey' => 'sd_section_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('SdFields', [
            'foreignKey' => 'sd_field_id',
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
            ->integer('row_no')
            ->requirePresence('row_no', 'create')
            ->notEmpty('row_no');

        $validator
            ->integer('field_length')
            ->requirePresence('field_length', 'create')
            ->notEmpty('field_length');

        $validator
            ->integer('field_height')
            ->requirePresence('field_height', 'create')
            ->notEmpty('field_height');

        $validator
            ->integer('field_start_at')
            ->requirePresence('field_start_at', 'create')
            ->notEmpty('field_start_at');

        $validator
            ->boolean('is_required')
            ->requirePresence('is_required', 'create')
            ->notEmpty('is_required');

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
        $rules->add($rules->existsIn(['sd_field_id'], 'SdFields'));

        return $rules;
    }
}
