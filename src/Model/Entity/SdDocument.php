<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SdDocument Entity
 *
 * @property int $id
 * @property int $sd_case_id
 * @property string $doc_classification
 * @property string $doc_description
 * @property string $doc_source
 * @property string $doc_name
 * @property string $doc_path
 * @property string $doc_type
 * @property int $doc_size
 * @property int $is_deleted
 * @property \Cake\I18n\FrozenTime $created_dt
 * @property \Cake\I18n\FrozenTime $updated_dt
 * @property int $created_by
 *
 * @property \App\Model\Entity\SdCase $sd_case
 */
class SdDocument extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'sd_case_id' => true,
        'doc_classification' => true,
        'doc_description' => true,
        'doc_source' => true,
        'doc_name' => true,
        'doc_path' => true,
        'doc_type' => true,
        'doc_size' => true,
        'is_deleted' => true,
        'created_dt' => true,
        'updated_dt' => true,
        'created_by' => true,
        'sd_case' => true
    ];
}
