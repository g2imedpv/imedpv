<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SdWorkflowActivity Entity
 *
 * @property int $id
 * @property int $sd_workflow_id
 * @property int $order_no
 * @property int $step_backward
 * @property string $due_day
 * @property string $activity_name
 * @property string $description
 *
 * @property \App\Model\Entity\SdWorkflow $sd_workflow
 * @property \App\Model\Entity\SdActivitySectionPermission[] $sd_activity_section_permissions
 * @property \App\Model\Entity\SdCase[] $sd_cases
 * @property \App\Model\Entity\SdUserAssignment[] $sd_user_assignments
 */
class SdWorkflowActivity extends Entity
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
        'sd_workflow_id' => true,
        'order_no' => true,
        'step_backward' => true,
        'due_day' => true,
        'activity_name' => true,
        'description' => true,
        'sd_workflow' => true,
        'sd_activity_section_permissions' => true,
        'sd_cases' => true,
        'sd_user_assignments' => true
    ];
}
