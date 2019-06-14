<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SdSectionStructuresR3Table;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SdSectionStructuresR3Table Test Case
 */
class SdSectionStructuresR3TableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SdSectionStructuresR3Table
     */
    public $SdSectionStructuresR3;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sd_section_structures_r3',
        'app.sd_sections',
        'app.sd_fields'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('SdSectionStructuresR3') ? [] : ['className' => SdSectionStructuresR3Table::class];
        $this->SdSectionStructuresR3 = TableRegistry::getTableLocator()->get('SdSectionStructuresR3', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SdSectionStructuresR3);

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
