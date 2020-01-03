<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SdSectionSummariesR3Table;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SdSectionSummariesR3Table Test Case
 */
class SdSectionSummariesR3TableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SdSectionSummariesR3Table
     */
    public $SdSectionSummariesR3;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sd_section_summaries_r3',
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
        $config = TableRegistry::getTableLocator()->exists('SdSectionSummariesR3') ? [] : ['className' => SdSectionSummariesR3Table::class];
        $this->SdSectionSummariesR3 = TableRegistry::getTableLocator()->get('SdSectionSummariesR3', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SdSectionSummariesR3);

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
