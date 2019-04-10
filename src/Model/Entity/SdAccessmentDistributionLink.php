<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SdAccessmentDistributionLink Entity
 *
 * @property int $id
 * @property int $sd_product_workflow_id
 * @property int $distribution
 *
 * @property \App\Model\Entity\SdProductWorkflow $sd_product_workflow
 */
class SdAccessmentDistributionLink extends Entity
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
        'distribution' => true,
        'sd_product_workflow' => true
    ];
}
