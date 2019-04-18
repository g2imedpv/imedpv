<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SdSectionSetsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SdSectionSetsTable Test Case
 */
class SdSectionSetsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SdSectionSetsTable
     */
    public $SdSectionSets;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sd_section_sets',
        'app.sd_sections',
        'app.sd_field_values'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('SdSectionSets') ? [] : ['className' => SdSectionSetsTable::class];
        $this->SdSectionSets = TableRegistry::getTableLocator()->get('SdSectionSets', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SdSectionSets);

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
