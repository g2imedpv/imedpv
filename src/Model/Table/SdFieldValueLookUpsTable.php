<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SdFieldValueLookUps Model
 *
 * @property \App\Model\Table\SdFieldsTable|\Cake\ORM\Association\BelongsTo $SdFields
 *
 * @method \App\Model\Entity\SdFieldValueLookUp get($primaryKey, $options = [])
 * @method \App\Model\Entity\SdFieldValueLookUp newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SdFieldValueLookUp[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SdFieldValueLookUp|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdFieldValueLookUp|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdFieldValueLookUp patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SdFieldValueLookUp[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SdFieldValueLookUp findOrCreate($search, callable $callback = null, $options = [])
 */
class SdFieldValueLookUpsTable extends Table
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
            ->scalar('value')
            ->maxLength('value', 100)
            ->requirePresence('value', 'create')
            ->notEmpty('value');

        $validator
            ->scalar('caption')
            ->maxLength('caption', 100)
            ->requirePresence('caption', 'create')
            ->notEmpty('caption');

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
