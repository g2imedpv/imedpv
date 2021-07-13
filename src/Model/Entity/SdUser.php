<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SdUser Entity
 *
 * @property int $id
 * @property int $sd_role_id
 * @property int $sd_company_id
 * @property string $firstname
 * @property string $lastname
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $thumbnail
 * @property string $site_number
 * @property string $site_name
 * @property string $title
 * @property string $phone_country_code
 * @property string $phone
 * @property string $extention
 * @property string $cell_country_code
 * @property string $cell_phone_no
 * @property string $verification
 * @property int $phone_alert
 * @property int $email_alert
 * @property int $is_never
 * @property \Cake\I18n\FrozenDate $account_expire_date
 * @property bool $is_email_verified
 * @property \Cake\I18n\FrozenTime $reset_password_expire_time
 * @property bool $is_import_user
 * @property int $is_medra
 * @property int $is_whodra
 * @property string $job_title
 * @property int $assign_protocol
 * @property int $status
 * @property string $default_language
 * @property int $created_by
 * @property \Cake\I18n\FrozenTime $created_dt
 * @property int $modified_by
 * @property \Cake\I18n\FrozenTime $modified_dt
 *
 * @property \App\Model\Entity\SdRole $sd_role
 * @property \App\Model\Entity\SdCompany $sd_company
 * @property \App\Model\Entity\SdActivityLog[] $sd_activity_logs
 * @property \App\Model\Entity\SdCase[] $sd_cases
 * @property \App\Model\Entity\SdProductWorkflow[] $sd_product_workflows
 * @property \App\Model\Entity\SdUserAssignment[] $sd_user_assignments
 */
class SdUser extends Entity
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
        'sd_role_id' => true,
        'sd_company_id' => true,
        'firstname' => true,
        'lastname' => true,
        'username' => true,
        'email' => true,
        'password' => true,
        'thumbnail' => true,
        'site_number' => true,
        'site_name' => true,
        'title' => true,
        'phone_country_code' => true,
        'phone' => true,
        'extention' => true,
        'cell_country_code' => true,
        'cell_phone_no' => true,
        'verification' => true,
        'phone_alert' => true,
        'email_alert' => true,
        'is_never' => true,
        'account_expire_date' => true,
        'is_email_verified' => true,
        'reset_password_expire_time' => true,
        'is_import_user' => true,
        'is_medra' => true,
        'is_whodra' => true,
        'job_title' => true,
        'assign_protocol' => true,
        'status' => true,
        'default_language' => true,
        'created_by' => true,
        'created_dt' => true,
        'modified_by' => true,
        'modified_dt' => true,
        'sd_role' => true,
        'sd_company' => true,
        'sd_activity_logs' => true,
        'sd_cases' => true,
        'sd_product_workflows' => true,
        'sd_user_assignments' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];
}
