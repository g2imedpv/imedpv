<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SdSectionSet Entity
 *
 * @property int $id
 * @property int $sd_section_id
 * @property int $sd_field_value_id
 * @property string $set_array
 *
 * @property \App\Model\Entity\SdSection $sd_section
 * @property \App\Model\Entity\SdFieldValue $sd_field_value
 */
class SdSectionSet extends Entity
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
        'sd_field_value_id' => true,
        'set_array' => true,
        'sd_section' => true,
        'sd_field_value' => true
    ];
}
