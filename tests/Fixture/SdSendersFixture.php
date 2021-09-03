<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SdSendersFixture
 *
 */
class SdSendersFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'sd_company_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => 'Sender Company', 'precision' => null, 'autoIncrement' => null],
        'type' => ['type' => 'integer', 'length' => 1, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => 'C.3.1 Sender Type', 'precision' => null, 'autoIncrement' => null],
        'organisation' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'C.3.2 Sender’s Organisation', 'precision' => null, 'fixed' => null],
        'department' => ['type' => 'string', 'length' => 60, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'C.3.3.1 Sender’s Department', 'precision' => null, 'fixed' => null],
        'title' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'C.3.3.2 Sender’s Title', 'precision' => null, 'fixed' => null],
        'given_name' => ['type' => 'string', 'length' => 60, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'C.3.3.3 Sender’s Given Name', 'precision' => null, 'fixed' => null],
        'middle_name' => ['type' => 'string', 'length' => 60, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'C.3.3.4 Sender’s Middle Name', 'precision' => null, 'fixed' => null],
        'family_name' => ['type' => 'string', 'length' => 60, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'C.3.3.5 Sender’s Family Name', 'precision' => null, 'fixed' => null],
        'street' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'C.3.4.1 Sender’s Street Address', 'precision' => null, 'fixed' => null],
        'city' => ['type' => 'string', 'length' => 35, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'C.3.4.2 Sender’s City', 'precision' => null, 'fixed' => null],
        'state' => ['type' => 'string', 'length' => 40, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'C.3.4.3 Sender’s State or Province', 'precision' => null, 'fixed' => null],
        'postcode' => ['type' => 'string', 'length' => 15, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'C.3.4.4 Sender’s Postcode', 'precision' => null, 'fixed' => null],
        'country' => ['type' => 'string', 'length' => 2, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'C.3.4.5 Sender’s Country Code', 'precision' => null, 'fixed' => null],
        'telephone' => ['type' => 'string', 'length' => 33, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'C.3.4.6 Sender’s Telephone', 'precision' => null, 'fixed' => null],
        'fax' => ['type' => 'string', 'length' => 33, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'C.3.4.7 Sender’s Fax', 'precision' => null, 'fixed' => null],
        'email' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'C.3.4.8 Sender’s E-mail Address', 'precision' => null, 'fixed' => null],
        'cn_mark' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'C.1.CN.3', 'precision' => null, 'fixed' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'sd_company_id' => ['type' => 'unique', 'columns' => ['sd_company_id'], 'length' => []],
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
                'sd_company_id' => 1,
                'type' => 1,
                'organisation' => 'Lorem ipsum dolor sit amet',
                'department' => 'Lorem ipsum dolor sit amet',
                'title' => 'Lorem ipsum dolor sit amet',
                'given_name' => 'Lorem ipsum dolor sit amet',
                'middle_name' => 'Lorem ipsum dolor sit amet',
                'family_name' => 'Lorem ipsum dolor sit amet',
                'street' => 'Lorem ipsum dolor sit amet',
                'city' => 'Lorem ipsum dolor sit amet',
                'state' => 'Lorem ipsum dolor sit amet',
                'postcode' => 'Lorem ipsum d',
                'country' => '',
                'telephone' => 'Lorem ipsum dolor sit amet',
                'fax' => 'Lorem ipsum dolor sit amet',
                'email' => 'Lorem ipsum dolor sit amet',
                'cn_mark' => 'Lorem ipsum dolor sit amet'
            ],
        ];
        parent::init();
    }
}
