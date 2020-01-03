<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SdSectionSummariesR3 Entity
 *
 * @property int $id
 * @property int $sd_section_id
 * @property string $fields
 *
 * @property \App\Model\Entity\SdSection $sd_section
 */
class SdSectionSummariesR3 extends Entity
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
        'fields' => true,
        'sd_section' => true
    ];
}
