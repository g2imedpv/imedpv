<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SdXmlStructure Entity
 *
 * @property int $id
 * @property string $tag
 * @property int $level
 * @property int $last_tag_id
 * @property string $sd_field_id
 * @property int $multiple
 *
 * @property \App\Model\Entity\LastTag $last_tag
 * @property \App\Model\Entity\SdField $sd_field
 */
class SdXmlStructure extends Entity
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
        'tag' => true,
        'level' => true,
        'last_tag_id' => true,
        'sd_field_id' => true,
        'multiple' => true,
        'last_tag' => true,
        'sd_field' => true
    ];
}
