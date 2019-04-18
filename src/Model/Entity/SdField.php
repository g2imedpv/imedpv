<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SdField Entity
 *
 * @property int $id
 * @property string $organization
 * @property string $descriptor
 * @property string $e2b_code
 * @property int $version
 * @property bool $is_e2b
 * @property int $field_length
 * @property string $field_type
 * @property string $field_label
 * @property int $sd_element_type_id
 * @property string $comment
 *
 * @property \App\Model\Entity\SdElementType $sd_element_type
 * @property \App\Model\Entity\SdFieldValueLookUp[] $sd_field_value_look_ups
 * @property \App\Model\Entity\SdFieldValue[] $sd_field_values
 * @property \App\Model\Entity\SdMedwatchPosition[] $sd_medwatch_positions
 * @property \App\Model\Entity\SdSectionStructure[] $sd_section_structures
 */
class SdField extends Entity
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
        'organization' => true,
        'descriptor' => true,
        'e2b_code' => true,
        'version' => true,
        'is_e2b' => true,
        'field_length' => true,
        'field_type' => true,
        'field_label' => true,
        'sd_element_type_id' => true,
        'comment' => true,
        'sd_element_type' => true,
        'sd_field_value_look_ups' => true,
        'sd_field_values' => true,
        'sd_medwatch_positions' => true,
        'sd_section_structures' => true
    ];
}
