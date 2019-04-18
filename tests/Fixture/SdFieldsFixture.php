<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SdFieldsFixture
 *
 */
class SdFieldsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'organization' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'descriptor' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'e2b_code' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'version' => ['type' => 'integer', 'length' => 3, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'is_e2b' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'field_length' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'field_type' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'AN- Alpha Numeric and special Character,N- Numeric,ANO-Alphanumeric Only 	', 'precision' => null, 'fixed' => null],
        'field_label' => ['type' => 'string', 'length' => 200, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'Form Element Label ', 'precision' => null, 'fixed' => null],
        'sd_element_type_id' => ['type' => 'integer', 'length' => 3, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'comment' => ['type' => 'string', 'length' => 1000, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'Comment for field', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'organization' => ['type' => 'index', 'columns' => ['organization'], 'length' => []],
            'descriptor' => ['type' => 'index', 'columns' => ['descriptor'], 'length' => []],
            'e2b_code' => ['type' => 'index', 'columns' => ['e2b_code'], 'length' => []],
            'version' => ['type' => 'index', 'columns' => ['version'], 'length' => []],
            'field_type' => ['type' => 'index', 'columns' => ['field_type'], 'length' => []],
            'field_label' => ['type' => 'index', 'columns' => ['field_label'], 'length' => []],
            'element_type_number' => ['type' => 'index', 'columns' => ['sd_element_type_id'], 'length' => []],
            'is_e2b' => ['type' => 'index', 'columns' => ['is_e2b'], 'length' => []],
            'field_length' => ['type' => 'index', 'columns' => ['field_length'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
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
                'organization' => 'Lorem ipsum dolor sit amet',
                'descriptor' => 'Lorem ipsum dolor sit amet',
                'e2b_code' => 'Lorem ipsum dolor sit amet',
                'version' => 1,
                'is_e2b' => 1,
                'field_length' => 1,
                'field_type' => 'Lorem ipsum dolor sit amet',
                'field_label' => 'Lorem ipsum dolor sit amet',
                'sd_element_type_id' => 1,
                'comment' => 'Lorem ipsum dolor sit amet'
            ],
        ];
        parent::init();
    }
}
