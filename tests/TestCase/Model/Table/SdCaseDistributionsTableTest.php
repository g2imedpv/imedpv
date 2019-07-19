<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SdCaseDistributionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SdCaseDistributionsTable Test Case
 */
class SdCaseDistributionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SdCaseDistributionsTable
     */
    public $SdCaseDistributions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sd_case_distributions',
        'app.sd_cases',
        'app.sd_workflow_activities',
        'app.sd_assessment_distribution_links',
        'app.sd_users',
        'app.sd_field_values',
        'app.sd_field_values05212019',
        'app.sd_field_values20190516'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('SdCaseDistributions') ? [] : ['className' => SdCaseDistributionsTable::class];
        $this->SdCaseDistributions = TableRegistry::getTableLocator()->get('SdCaseDistributions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SdCaseDistributions);

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
