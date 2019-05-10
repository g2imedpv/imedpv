<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SdContact Entity
 *
 * @property int $id
 * @property int $contact_type
 * @property int $authority
 * @property bool $data_privacy
 * @property int $blinded_report
 * @property string $contactId
 * @property int $preferred_route
 * @property int $format_type
 * @property string $title
 * @property string $given_name
 * @property string $family_name
 * @property string $middle_name
 * @property string $address
 * @property string $address_extension
 * @property string $city
 * @property string $state_province
 * @property string $zipcode
 * @property int $country
 * @property string $phone
 * @property string $phone_extension
 * @property string $fax
 * @property string $fax_extension
 * @property string $email_address
 * @property string $website
 */
class SdContact extends Entity
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
        'contact_type' => true,
        'authority' => true,
        'data_privacy' => true,
        'blinded_report' => true,
        'contactId' => true,
        'preferred_route' => true,
        'format_type' => true,
        'title' => true,
        'given_name' => true,
        'family_name' => true,
        'middle_name' => true,
        'address' => true,
        'address_extension' => true,
        'city' => true,
        'state_province' => true,
        'zipcode' => true,
        'country' => true,
        'phone' => true,
        'phone_extension' => true,
        'fax' => true,
        'fax_extension' => true,
        'email_address' => true,
        'website' => true
    ];
}
