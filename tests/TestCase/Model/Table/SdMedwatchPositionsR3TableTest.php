<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SdMedwatchPositionsR3Table;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SdMedwatchPositionsR3Table Test Case
 */
class SdMedwatchPositionsR3TableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SdMedwatchPositionsR3Table
     */
    public $SdMedwatchPositionsR3;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sd_medwatch_positions_r3',
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
        $config = TableRegistry::getTableLocator()->exists('SdMedwatchPositionsR3') ? [] : ['className' => SdMedwatchPositionsR3Table::class];
        $this->SdMedwatchPositionsR3 = TableRegistry::getTableLocator()->get('SdMedwatchPositionsR3', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SdMedwatchPositionsR3);

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
