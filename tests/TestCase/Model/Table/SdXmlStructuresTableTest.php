<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SdXmlStructuresTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SdXmlStructuresTable Test Case
 */
class SdXmlStructuresTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SdXmlStructuresTable
     */
    public $SdXmlStructures;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sd_xml_structures',
        'app.last_tags',
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
        $config = TableRegistry::getTableLocator()->exists('SdXmlStructures') ? [] : ['className' => SdXmlStructuresTable::class];
        $this->SdXmlStructures = TableRegistry::getTableLocator()->get('SdXmlStructures', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SdXmlStructures);

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
