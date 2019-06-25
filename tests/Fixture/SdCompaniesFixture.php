<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SdCompaniesFixture
 *
 */
class SdCompaniesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'sd_user_type_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => 'User Type', 'precision' => null, 'autoIncrement' => null],
        'company_name' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'company_email' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'website' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'address_line1' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'address_line2' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'zipcode' => ['type' => 'string', 'length' => 20, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'city' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'state' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'country' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'cell_country_code' => ['type' => 'string', 'length' => 5, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'cell_phone_no' => ['type' => 'string', 'length' => 20, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'phone1_country_code' => ['type' => 'string', 'length' => 5, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'phone1' => ['type' => 'string', 'length' => 20, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'extention1' => ['type' => 'string', 'length' => 5, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'phone2_country_code' => ['type' => 'string', 'length' => 5, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'phone2' => ['type' => 'string', 'length' => 20, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'extention2' => ['type' => 'string', 'length' => 5, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'fax1_country_code' => ['type' => 'string', 'length' => 5, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'fax1' => ['type' => 'string', 'length' => 20, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'fax2_country_code' => ['type' => 'string', 'length' => 5, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'fax2' => ['type' => 'string', 'length' => 20, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'transaction_currency' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'no_of_billing_cycle' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'current_billing_cycle' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'no_of_whodra' => ['type' => 'smallinteger', 'length' => 3, 'unsigned' => false, 'null' => true, 'default' => '0', 'comment' => '', 'precision' => null],
        'status' => ['type' => 'smallinteger', 'length' => 1, 'unsigned' => false, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null],
        'un_paid' => ['type' => 'smallinteger', 'length' => 1, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '0-paid, 1-un paid', 'precision' => null],
        'is_medra' => ['type' => 'smallinteger', 'length' => 1, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '1-allow meddra lib', 'precision' => null],
        'is_whodra' => ['type' => 'smallinteger', 'length' => 1, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'create_by' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created_dt' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modify_by' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'modified_dt' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'product_abbreviation' => ['type' => 'string', 'length' => 5, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'company_type' => ['type' => 'index', 'columns' => ['sd_user_type_id'], 'length' => []],
            'fax_no1' => ['type' => 'index', 'columns' => ['fax1'], 'length' => []],
            'phone_no2' => ['type' => 'index', 'columns' => ['phone2'], 'length' => []],
            'fax_no2' => ['type' => 'index', 'columns' => ['fax2'], 'length' => []],
            'create_by' => ['type' => 'index', 'columns' => ['create_by'], 'length' => []],
            'modify_by' => ['type' => 'index', 'columns' => ['modify_by'], 'length' => []],
            'city' => ['type' => 'index', 'columns' => ['city', 'state', 'country'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'company_name' => ['type' => 'unique', 'columns' => ['company_name'], 'length' => []],
            'phone_no1' => ['type' => 'unique', 'columns' => ['phone1'], 'length' => []],
            'company_email' => ['type' => 'unique', 'columns' => ['company_email'], 'length' => []],
            'website' => ['type' => 'unique', 'columns' => ['website'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'sd_user_type_id' => 1,
                'company_name' => 'Lorem ipsum dolor sit amet',
                'company_email' => 'Lorem ipsum dolor sit amet',
                'website' => 'Lorem ipsum dolor sit amet',
                'address_line1' => 'Lorem ipsum dolor sit amet',
                'address_line2' => 'Lorem ipsum dolor sit amet',
                'zipcode' => 'Lorem ipsum dolor ',
                'city' => 'Lorem ipsum dolor sit amet',
                'state' => 'Lorem ipsum dolor sit amet',
                'country' => 'Lorem ipsum dolor sit amet',
                'cell_country_code' => 'Lor',
                'cell_phone_no' => 'Lorem ipsum dolor ',
                'phone1_country_code' => 'Lor',
                'phone1' => 'Lorem ipsum dolor ',
                'extention1' => 'Lor',
                'phone2_country_code' => 'Lor',
                'phone2' => 'Lorem ipsum dolor ',
                'extention2' => 'Lor',
                'fax1_country_code' => 'Lor',
                'fax1' => 'Lorem ipsum dolor ',
                'fax2_country_code' => 'Lor',
                'fax2' => 'Lorem ipsum dolor ',
                'transaction_currency' => 1,
                'no_of_billing_cycle' => 1,
                'current_billing_cycle' => 1,
                'no_of_whodra' => 1,
                'status' => 1,
                'un_paid' => 1,
                'is_medra' => 1,
                'is_whodra' => 1,
                'create_by' => 1,
                'created_dt' => '2019-06-25 18:01:45',
                'modify_by' => 1,
                'modified_dt' => '2019-06-25 18:01:45',
                'product_abbreviation' => 'Lor'
            ],
        ];
        parent::init();
    }
}
