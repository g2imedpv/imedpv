<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SdActivityLog Entity
 *
 * @property int $id
 * @property int $sd_user_id
 * @property string $controller
 * @property string $action
 * @property int $sd_section_value_id
 * @property string $data_changed
 * @property \Cake\I18n\FrozenTime $updated_time
 * @property int $sd_actvity_id
 *
 * @property \App\Model\Entity\SdUser $sd_user
 * @property \App\Model\Entity\SdSectionValue $sd_section_value
 * @property \App\Model\Entity\SdActvity $sd_actvity
 */
class SdActivityLog extends Entity
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
        'sd_user_id' => true,
        'controller' => true,
        'action' => true,
        'sd_section_value_id' => true,
        'data_changed' => true,
        'updated_time' => true,
        'sd_actvity_id' => true,
        'sd_user' => true,
        'sd_section_value' => true,
        'sd_actvity' => true
    ];
}
