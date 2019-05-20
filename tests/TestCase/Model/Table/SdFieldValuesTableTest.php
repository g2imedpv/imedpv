<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SdFieldValuesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SdFieldValuesTable Test Case
 */
class SdFieldValuesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SdFieldValuesTable
     */
    public $SdFieldValues;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sd_field_values',
        'app.sd_cases',
        'app.sd_fields',
        'app.sd_case_distributions',
        'app.sd_section_sets'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('SdFieldValues') ? [] : ['className' => SdFieldValuesTable::class];
        $this->SdFieldValues = TableRegistry::getTableLocator()->get('SdFieldValues', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SdFieldValues);

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
