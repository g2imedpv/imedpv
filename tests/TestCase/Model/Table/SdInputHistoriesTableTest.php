<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SdInputHistoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SdInputHistoriesTable Test Case
 */
class SdInputHistoriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SdInputHistoriesTable
     */
    public $SdInputHistories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sd_input_histories',
        'app.sd_field_values',
        'app.sd_users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('SdInputHistories') ? [] : ['className' => SdInputHistoriesTable::class];
        $this->SdInputHistories = TableRegistry::getTableLocator()->get('SdInputHistories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SdInputHistories);

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
