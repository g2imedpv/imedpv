<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SdMedwatchPositionsR3 Model
 *
 * @property \App\Model\Table\SdFieldsTable|\Cake\ORM\Association\BelongsTo $SdFields
 *
 * @method \App\Model\Entity\SdMedwatchPositionsR3 get($primaryKey, $options = [])
 * @method \App\Model\Entity\SdMedwatchPositionsR3 newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SdMedwatchPositionsR3[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SdMedwatchPositionsR3|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdMedwatchPositionsR3|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdMedwatchPositionsR3 patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SdMedwatchPositionsR3[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SdMedwatchPositionsR3 findOrCreate($search, callable $callback = null, $options = [])
 */
class SdMedwatchPositionsR3Table extends Table
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

        $this->setTable('sd_medwatch_positions_r3');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('SdFields', [
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
            ->scalar('medwatch_no')
            ->maxLength('medwatch_no', 4)
            ->allowEmpty('medwatch_no');

        $validator
            ->scalar('field_name')
            ->maxLength('field_name', 20)
            ->allowEmpty('field_name');

        $validator
            ->integer('position_top')
            ->allowEmpty('position_top');

        $validator
            ->integer('position_left')
            ->allowEmpty('position_left');

        $validator
            ->integer('position_width')
            ->allowEmpty('position_width');

        $validator
            ->integer('position_height')
            ->allowEmpty('position_height');

        $validator
            ->integer('set_number')
            ->allowEmpty('set_number');

        $validator
            ->integer('value_type')
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
