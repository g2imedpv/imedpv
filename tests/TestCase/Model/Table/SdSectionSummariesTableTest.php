<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SdSectionSummariesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SdSectionSummariesTable Test Case
 */
class SdSectionSummariesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SdSectionSummariesTable
     */
    public $SdSectionSummaries;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sd_section_summaries',
        'app.sd_sections'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('SdSectionSummaries') ? [] : ['className' => SdSectionSummariesTable::class];
        $this->SdSectionSummaries = TableRegistry::getTableLocator()->get('SdSectionSummaries', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SdSectionSummaries);

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
