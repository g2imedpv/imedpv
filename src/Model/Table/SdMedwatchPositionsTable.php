<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SdMedwatchPositions Model
 *
 * @property \App\Model\Table\SdFieldsTable|\Cake\ORM\Association\BelongsTo $SdFields
 *
 * @method \App\Model\Entity\SdMedwatchPosition get($primaryKey, $options = [])
 * @method \App\Model\Entity\SdMedwatchPosition newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SdMedwatchPosition[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SdMedwatchPosition|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdMedwatchPosition|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdMedwatchPosition patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SdMedwatchPosition[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SdMedwatchPosition findOrCreate($search, callable $callback = null, $options = [])
 */
class SdMedwatchPositionsTable extends Table
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

        $this->setTable('sd_medwatch_positions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

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
            ->scalar('medwatch_no')
            ->maxLength('medwatch_no', 4)
            ->requirePresence('medwatch_no', 'create')
            ->notEmpty('medwatch_no');

        $validator
            ->scalar('field_name')
            ->maxLength('field_name', 30)
            ->requirePresence('field_name', 'create')
            ->notEmpty('field_name');

        $validator
            ->integer('position_top')
            ->requirePresence('position_top', 'create')
            ->notEmpty('position_top');

        $validator
            ->integer('position_left')
            ->requirePresence('position_left', 'create')
            ->notEmpty('position_left');

        $validator
            ->integer('position_width')
            ->requirePresence('position_width', 'create')
            ->notEmpty('position_width');

        $validator
            ->integer('position_height')
            ->requirePresence('position_height', 'create')
            ->notEmpty('position_height');

        $validator
            ->allowEmpty('set_number');

        $validator
            ->scalar('value_type')
            ->maxLength('value_type', 100)
            ->allowEmpty('value_type');

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
        $rules->add($rules->existsIn(['sd_field_id'], 'SdFields'));

        return $rules;
    }
}
