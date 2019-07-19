<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SdAssessmentDistributionLinksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SdAssessmentDistributionLinksTable Test Case
 */
class SdAssessmentDistributionLinksTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SdAssessmentDistributionLinksTable
     */
    public $SdAssessmentDistributionLinks;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sd_assessment_distribution_links',
        'app.sd_product_workflows'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('SdAssessmentDistributionLinks') ? [] : ['className' => SdAssessmentDistributionLinksTable::class];
        $this->SdAssessmentDistributionLinks = TableRegistry::getTableLocator()->get('SdAssessmentDistributionLinks', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SdAssessmentDistributionLinks);

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
