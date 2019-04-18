<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SdSectionSets Model
 *
 * @property \App\Model\Table\SdSectionsTable|\Cake\ORM\Association\BelongsTo $SdSections
 * @property \App\Model\Table\SdFieldValuesTable|\Cake\ORM\Association\BelongsTo $SdFieldValues
 *
 * @method \App\Model\Entity\SdSectionSet get($primaryKey, $options = [])
 * @method \App\Model\Entity\SdSectionSet newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SdSectionSet[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SdSectionSet|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdSectionSet|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdSectionSet patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SdSectionSet[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SdSectionSet findOrCreate($search, callable $callback = null, $options = [])
 */
class SdSectionSetsTable extends Table
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

        $this->setTable('sd_section_sets');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('SdSections', [
            'foreignKey' => 'sd_section_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('SdFieldValues', [
            'foreignKey' => 'sd_field_value_id',
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
            ->scalar('set_array')
            ->maxLength('set_array', 100)
            ->requirePresence('set_array', 'create')
            ->notEmpty('set_array');

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
        $rules->add($rules->existsIn(['sd_field_value_id'], 'SdFieldValues'));

        return $rules;
    }
}
