<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SdCompany Entity
 *
 * @property int $id
 * @property int $sd_user_type_id
 * @property string $company_name
 * @property string $company_email
 * @property string $website
 * @property string $address_line1
 * @property string $address_line2
 * @property string $zipcode
 * @property string $city
 * @property string $state
 * @property string $country
 * @property string $cell_country_code
 * @property string $cell_phone_no
 * @property string $phone1_country_code
 * @property string $phone1
 * @property string $extention1
 * @property string $phone2_country_code
 * @property string $phone2
 * @property string $extention2
 * @property string $fax1_country_code
 * @property string $fax1
 * @property string $fax2_country_code
 * @property string $fax2
 * @property int $transaction_currency
 * @property int $no_of_billing_cycle
 * @property int $current_billing_cycle
 * @property int $no_of_whodra
 * @property int $status
 * @property int $un_paid
 * @property int $is_medra
 * @property int $is_whodra
 * @property int $create_by
 * @property \Cake\I18n\FrozenTime $created_dt
 * @property int $modify_by
 * @property \Cake\I18n\FrozenTime $modified_dt
 * @property string $company_abbreviation
 *
 * @property \App\Model\Entity\SdUserType $sd_user_type
 * @property \App\Model\Entity\SdProductWorkflow[] $sd_product_workflows
 * @property \App\Model\Entity\SdProduct[] $sd_products
 * @property \App\Model\Entity\SdUser[] $sd_users
 */
class SdCompany extends Entity
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
        'sd_user_type_id' => true,
        'company_name' => true,
        'company_email' => true,
        'website' => true,
        'address_line1' => true,
        'address_line2' => true,
        'zipcode' => true,
        'city' => true,
        'state' => true,
        'country' => true,
        'cell_country_code' => true,
        'cell_phone_no' => true,
        'phone1_country_code' => true,
        'phone1' => true,
        'extention1' => true,
        'phone2_country_code' => true,
        'phone2' => true,
        'extention2' => true,
        'fax1_country_code' => true,
        'fax1' => true,
        'fax2_country_code' => true,
        'fax2' => true,
        'transaction_currency' => true,
        'no_of_billing_cycle' => true,
        'current_billing_cycle' => true,
        'no_of_whodra' => true,
        'status' => true,
        'un_paid' => true,
        'is_medra' => true,
        'is_whodra' => true,
        'create_by' => true,
        'created_dt' => true,
        'modify_by' => true,
        'modified_dt' => true,
        'company_abbreviation' => true,
        'sd_user_type' => true,
        'sd_product_workflows' => true,
        'sd_products' => true,
        'sd_users' => true
    ];
}
