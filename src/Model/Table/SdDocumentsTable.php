<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SdDocuments Model
 *
 * @property \App\Model\Table\SdCasesTable|\Cake\ORM\Association\BelongsTo $SdCases
 *
 * @method \App\Model\Entity\SdDocument get($primaryKey, $options = [])
 * @method \App\Model\Entity\SdDocument newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SdDocument[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SdDocument|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdDocument|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdDocument patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SdDocument[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SdDocument findOrCreate($search, callable $callback = null, $options = [])
 */
class SdDocumentsTable extends Table
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

        $this->setTable('sd_documents');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('SdCases', [
            'foreignKey' => 'sd_case_id',
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
            ->scalar('doc_classification')
            ->maxLength('doc_classification', 255)
            ->allowEmpty('doc_classification');

        $validator
            ->scalar('doc_description')
            ->allowEmpty('doc_description');

        $validator
            ->scalar('doc_source')
            ->allowEmpty('doc_source');

        $validator
            ->scalar('doc_name')
            ->maxLength('doc_name', 255)
            ->allowEmpty('doc_name');

        $validator
            ->scalar('doc_path')
            ->maxLength('doc_path', 255)
            ->allowEmpty('doc_path');

        $validator
            ->scalar('doc_type')
            ->maxLength('doc_type', 40)
            ->allowEmpty('doc_type');

        $validator
            ->integer('doc_size')
            ->requirePresence('doc_size', 'create')
            ->notEmpty('doc_size');

        $validator
            ->requirePresence('is_deleted', 'create')
            ->notEmpty('is_deleted');

        $validator
            ->dateTime('created_dt')
            ->allowEmpty('created_dt');

        $validator
            ->dateTime('updated_dt')
            ->allowEmpty('updated_dt');

        $validator
            ->integer('created_by')
            ->allowEmpty('created_by');

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
        $rules->add($rules->existsIn(['sd_case_id'], 'SdCases'));

        return $rules;
    }
}
