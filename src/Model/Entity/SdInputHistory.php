<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SdInputHistory Entity
 *
 * @property int $id
 * @property int $sd_field_value_id
 * @property string $input
 * @property int $sd_user_id
 * @property \Cake\I18n\FrozenTime $time_changed
 * @property int $sd_workflow_activity_id
 *
 * @property \App\Model\Entity\SdFieldValue $sd_field_value
 * @property \App\Model\Entity\SdUser $sd_user
 */
class SdInputHistory extends Entity
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
        'sd_field_value_id' => true,
        'input' => true,
        'sd_user_id' => true,
        'time_changed' => true,
        'sd_workflow_activity_id' => true,
        'sd_field_value' => true,
        'sd_user' => true
    ];
}
