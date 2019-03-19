<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SdSections Model
 *
 * @property \App\Model\Table\SdTabsTable|\Cake\ORM\Association\BelongsTo $SdTabs
 * @property |\Cake\ORM\Association\HasMany $SdActivitySectionPermissions
 * @property \App\Model\Table\SdSectionStructuresTable|\Cake\ORM\Association\HasMany $SdSectionStructures
 *
 * @method \App\Model\Entity\SdSection get($primaryKey, $options = [])
 * @method \App\Model\Entity\SdSection newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SdSection[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SdSection|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdSection|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SdSection patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SdSection[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SdSection findOrCreate($search, callable $callback = null, $options = [])
 */
class SdSectionsTable extends Table
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

        $this->setTable('sd_sections');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('SdTabs', [
            'foreignKey' => 'sd_tab_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('SdActivitySectionPermissions', [
            'foreignKey' => 'sd_section_id'
        ]);
        $this->hasMany('SdSectionStructures', [
            'foreignKey' => 'sd_section_id'
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
            ->scalar('section_name')
            ->maxLength('section_name', 255)
            ->requirePresence('section_name', 'create')
            ->notEmpty('section_name');

        $validator
            ->requirePresence('section_level', 'create')
            ->notEmpty('section_level');

        $validator
            ->scalar('child_section')
            ->maxLength('child_section', 50)
            ->requirePresence('child_section', 'create')
            ->notEmpty('child_section');

        $validator
            ->boolean('is_addable')
            ->requirePresence('is_addable', 'create')
            ->notEmpty('is_addable');

        $validator
            ->integer('display_order')
            ->requirePresence('display_order', 'create')
            ->notEmpty('display_order');

        $validator
            ->boolean('status')
            ->requirePresence('status', 'create')
            ->notEmpty('status');

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
        $rules->add($rules->existsIn(['sd_tab_id'], 'SdTabs'));

        return $rules;
    }
}
