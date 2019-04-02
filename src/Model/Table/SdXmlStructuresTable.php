<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SdXmlStructures Model
 *
 * @property \App\Model\Table\LastTagsTable|\Cake\ORM\Association\BelongsTo $LastTags
 * @property \App\Model\Table\SdFieldsTable|\Cake\ORM\Association\BelongsTo $SdFields
 *
 * @method \App\Model\Entity\SdXmlStructure get($primaryKey, $options = [])
 * @method \App\Model\Entity\SdXmlStructure newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SdXmlStructure[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SdXmlStructure|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdXmlStructure|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdXmlStructure patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SdXmlStructure[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SdXmlStructure findOrCreate($search, callable $callback = null, $options = [])
 */
class SdXmlStructuresTable extends Table
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

        $this->setTable('sd_xml_structures');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('LastTags', [
            'foreignKey' => 'last_tag_id',
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
            ->scalar('tag')
            ->maxLength('tag', 100)
            ->requirePresence('tag', 'create')
            ->notEmpty('tag');

        $validator
            ->integer('level')
            ->requirePresence('level', 'create')
            ->notEmpty('level');

        $validator
            ->integer('multiple')
            ->requirePresence('multiple', 'create')
            ->notEmpty('multiple');

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
        $rules->add($rules->existsIn(['last_tag_id'], 'LastTags'));
        $rules->add($rules->existsIn(['sd_field_id'], 'SdFields'));

        return $rules;
    }
}
