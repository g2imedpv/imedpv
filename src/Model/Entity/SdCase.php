<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SdCase Entity
 *
 * @property int $id
 * @property int $sd_product_workflow_id
 * @property string $caseNo
 * @property int $sd_workflow_activity_id
 * @property int $status
 * @property int $sd_user_id
 * @property int $priority
 * @property string $activity_due_date
 * @property string $submission_due_date
 * @property int $product_type
 * @property string $classification
 *
 * @property \App\Model\Entity\SdProductWorkflow $sd_product_workflow
 * @property \App\Model\Entity\SdWorkflowActivity $sd_workflow_activity
 * @property \App\Model\Entity\SdUser $sd_user
 * @property \App\Model\Entity\SdCaseGeneralInfo[] $sd_case_general_infos
 * @property \App\Model\Entity\SdFieldValue[] $sd_field_values
 */
class SdCase extends Entity
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
        'sd_product_workflow_id' => true,
        'caseNo' => true,
        'sd_workflow_activity_id' => true,
        'status' => true,
        'sd_user_id' => true,
        'priority' => true,
        'activity_due_date' => true,
        'submission_due_date' => true,
        'product_type' => true,
        'classification' => true,
        'sd_product_workflow' => true,
        'sd_workflow_activity' => true,
        'sd_user' => true,
        'sd_case_general_infos' => true,
        'sd_field_values' => true
    ];
}
