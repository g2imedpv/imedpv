<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SdFieldsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SdFieldsTable Test Case
 */
class SdFieldsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SdFieldsTable
     */
    public $SdFields;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sd_fields',
        'app.sd_element_types',
        'app.sd_field_value_look_ups',
        'app.sd_field_values',
        'app.sd_medwatch_positions',
        'app.sd_section_structures'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('SdFields') ? [] : ['className' => SdFieldsTable::class];
        $this->SdFields = TableRegistry::getTableLocator()->get('SdFields', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SdFields);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
