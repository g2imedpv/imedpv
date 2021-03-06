<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SdSectionValuesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SdSectionValuesTable Test Case
 */
class SdSectionValuesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SdSectionValuesTable
     */
    public $SdSectionValues;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sd_section_values',
        'app.sd_section_structures',
        'app.sd_activity_log'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('SdSectionValues') ? [] : ['className' => SdSectionValuesTable::class];
        $this->SdSectionValues = TableRegistry::getTableLocator()->get('SdSectionValues', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SdSectionValues);

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
