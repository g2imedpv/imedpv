<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SdFieldValue Entity
 *
 * @property int $id
 * @property string $sd_case_id
 * @property int $sd_field_id
 * @property string $set_number
 * @property string $field_value
 * @property \Cake\I18n\FrozenTime $created_time
 * @property bool $status
 * @property int $sd_case_distribution_id
 *
 * @property \App\Model\Entity\SdCase $sd_case
 * @property \App\Model\Entity\SdField $sd_field
 * @property \App\Model\Entity\SdCaseDistribution $sd_case_distribution
 * @property \App\Model\Entity\SdSectionSet[] $sd_section_sets
 */
class SdFieldValue extends Entity
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
        'sd_field_id' => true,
        'set_number' => true,
        'field_value' => true,
        'created_time' => true,
        'status' => true,
        'sd_case_distribution_id' => true,
        'sd_case' => true,
        'sd_field' => true,
        'sd_case_distribution' => true,
        'sd_section_sets' => true
    ];
}
