<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SdCaseDistribution Entity
 *
 * @property int $id
 * @property int $sd_case_id
 * @property int $sd_workflow_activity_id
 * @property int $sd_assessment_distribution_link_id
 * @property int $sd_user_id
 *
 * @property \App\Model\Entity\SdCase $sd_case
 * @property \App\Model\Entity\SdWorkflowActivity $sd_workflow_activity
 * @property \App\Model\Entity\SdAssessmentDistributionLink $sd_assessment_distribution_link
 * @property \App\Model\Entity\SdUser $sd_user
 * @property \App\Model\Entity\SdFieldValue[] $sd_field_values
 * @property \App\Model\Entity\SdFieldValues05212019[] $sd_field_values05212019
 * @property \App\Model\Entity\SdFieldValues20190516[] $sd_field_values20190516
 */
class SdCaseDistribution extends Entity
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
        'sd_case_id' => true,
        'sd_workflow_activity_id' => true,
        'sd_assessment_distribution_link_id' => true,
        'sd_user_id' => true,
        'sd_case' => true,
        'sd_workflow_activity' => true,
        'sd_assessment_distribution_link' => true,
        'sd_user' => true,
        'sd_field_values' => true,
        'sd_field_values05212019' => true,
        'sd_field_values20190516' => true
    ];
}
