<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SdFields Model
 *
 * @property \App\Model\Table\SdElementTypesTable|\Cake\ORM\Association\BelongsTo $SdElementTypes
 * @property \App\Model\Table\SdFieldValueLookUpsTable|\Cake\ORM\Association\HasMany $SdFieldValueLookUps
 * @property \App\Model\Table\SdFieldValuesTable|\Cake\ORM\Association\HasMany $SdFieldValues
 * @property \App\Model\Table\SdMedwatchPositionsTable|\Cake\ORM\Association\HasMany $SdMedwatchPositions
 * @property \App\Model\Table\SdSectionStructuresTable|\Cake\ORM\Association\HasMany $SdSectionStructures
 * @property |\Cake\ORM\Association\HasMany $SdUserPreferrences
 *
 * @method \App\Model\Entity\SdField get($primaryKey, $options = [])
 * @method \App\Model\Entity\SdField newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SdField[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SdField|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdField|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdField patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SdField[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SdField findOrCreate($search, callable $callback = null, $options = [])
 */
class SdFieldsTable extends Table
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

        $this->setTable('sd_fields');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('SdElementTypes', [
            'foreignKey' => 'sd_element_type_id'
        ]);
        $this->hasMany('SdFieldValueLookUps', [
            'foreignKey' => 'sd_field_id'
        ]);
        $this->hasMany('SdFieldValues', [
            'foreignKey' => 'sd_field_id'
        ]);
        $this->hasMany('SdMedwatchPositions', [
            'foreignKey' => 'sd_field_id'
        ]);
        $this->hasMany('SdSectionStructures', [
            'foreignKey' => 'sd_field_id'
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
            ->scalar('organization')
            ->maxLength('organization', 50)
            ->allowEmpty('organization');

        $validator
            ->scalar('descriptor')
            ->maxLength('descriptor', 100)
            ->requirePresence('descriptor', 'create')
            ->notEmpty('descriptor');

        $validator
            ->scalar('e2b_code')
            ->maxLength('e2b_code', 50)
            ->requirePresence('e2b_code', 'create')
            ->notEmpty('e2b_code');

        $validator
            ->integer('version')
            ->allowEmpty('version');

        $validator
            ->boolean('is_e2b')
            ->requirePresence('is_e2b', 'create')
            ->notEmpty('is_e2b');

        $validator
            ->integer('field_length')
            ->requirePresence('field_length', 'create')
            ->notEmpty('field_length');

        $validator
            ->scalar('field_type')
            ->maxLength('field_type', 50)
            ->allowEmpty('field_type');

        $validator
            ->scalar('field_label')
            ->maxLength('field_label', 200)
            ->allowEmpty('field_label');

        $validator
            ->scalar('comment')
            ->maxLength('comment', 1000)
            ->requirePresence('comment', 'create')
            ->notEmpty('comment');

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
        $rules->add($rules->existsIn(['sd_element_type_id'], 'SdElementTypes'));

        return $rules;
    }
}
