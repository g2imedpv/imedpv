<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SdSectionsR3 Entity
 *
 * @property int $id
 * @property string $section_name
 * @property int $section_level
 * @property string $child_section
 * @property int $parent_section
 * @property int $sd_tab_id
 * @property bool $is_addable
 * @property int $display_order
 * @property bool $status
 * @property int $section_type
 *
 * @property \App\Model\Entity\SdTab $sd_tab
 */
class SdSectionsR3 extends Entity
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
        'section_name' => true,
        'section_level' => true,
        'child_section' => true,
        'parent_section' => true,
        'sd_tab_id' => true,
        'is_addable' => true,
        'display_order' => true,
        'status' => true,
        'section_type' => true,
        'sd_tab' => true
    ];
}
