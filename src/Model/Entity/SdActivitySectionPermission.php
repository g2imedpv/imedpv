<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SdActivitySectionPermission Entity
 *
 * @property int $id
 * @property int $sd_workflow_activity_id
 * @property int $sd_section_id
 * @property int $action
 *
 * @property \App\Model\Entity\SdWorkflowActivity $sd_workflow_activity
 * @property \App\Model\Entity\SdSection $sd_section
 */
class SdActivitySectionPermission extends Entity
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
        'sd_workflow_activity_id' => true,
        'sd_section_id' => true,
        'action' => true,
        'sd_workflow_activity' => true,
        'sd_section' => true
    ];
}
