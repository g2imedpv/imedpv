<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SdSectionStructuresR3 Entity
 *
 * @property int $id
 * @property int $sd_section_id
 * @property int $sd_field_id
 * @property int $row_no
 * @property int $field_length
 * @property int $field_height
 * @property int $field_start_at
 * @property bool $is_required
 *
 * @property \App\Model\Entity\SdSection $sd_section
 * @property \App\Model\Entity\SdField $sd_field
 */
class SdSectionStructuresR3 extends Entity
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
        'sd_section_id' => true,
        'sd_field_id' => true,
        'row_no' => true,
        'field_length' => true,
        'field_height' => true,
        'field_start_at' => true,
        'is_required' => true,
        'sd_section' => true,
        'sd_field' => true
    ];
}
