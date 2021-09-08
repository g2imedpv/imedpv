<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SdSendersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SdSendersTable Test Case
 */
class SdSendersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SdSendersTable
     */
    public $SdSenders;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sd_senders',
        'app.sd_companies'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('SdSenders') ? [] : ['className' => SdSendersTable::class];
        $this->SdSenders = TableRegistry::getTableLocator()->get('SdSenders', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SdSenders);

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
