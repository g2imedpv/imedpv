<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SdCasesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SdCasesTable Test Case
 */
class SdCasesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SdCasesTable
     */
    public $SdCases;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sd_cases',
        'app.sd_product_workflows',
        'app.sd_workflow_activities',
        'app.sd_users',
        'app.sd_case_distributions',
        'app.sd_case_histories',
        'app.sd_documents',
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
        $config = TableRegistry::getTableLocator()->exists('SdCases') ? [] : ['className' => SdCasesTable::class];
        $this->SdCases = TableRegistry::getTableLocator()->get('SdCases', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SdCases);

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
