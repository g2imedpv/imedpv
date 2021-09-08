<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SdSender Entity
 *
 * @property int $id
 * @property int $sd_company_id
 * @property int $type
 * @property string $organisation
 * @property string $department
 * @property string $title
 * @property string $given_name
 * @property string $middle_name
 * @property string $family_name
 * @property string $street
 * @property string $city
 * @property string $state
 * @property string $postcode
 * @property string $country
 * @property string $telephone
 * @property string $fax
 * @property string $email
 * @property string $cn_mark
 *
 * @property \App\Model\Entity\SdCompany $sd_company
 */
class SdSender extends Entity
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
        'sd_company_id' => true,
        'type' => true,
        'organisation' => true,
        'department' => true,
        'title' => true,
        'given_name' => true,
        'middle_name' => true,
        'family_name' => true,
        'street' => true,
        'city' => true,
        'state' => true,
        'postcode' => true,
        'country' => true,
        'telephone' => true,
        'fax' => true,
        'email' => true,
        'cn_mark' => true,
        'sd_company' => true
    ];
}
