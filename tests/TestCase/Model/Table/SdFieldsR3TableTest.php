<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SdFieldsR3Table;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SdFieldsR3Table Test Case
 */
class SdFieldsR3TableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SdFieldsR3Table
     */
    public $SdFieldsR3;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sd_fields_r3',
        'app.sd_element_types'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('SdFieldsR3') ? [] : ['className' => SdFieldsR3Table::class];
        $this->SdFieldsR3 = TableRegistry::getTableLocator()->get('SdFieldsR3', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SdFieldsR3);

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
