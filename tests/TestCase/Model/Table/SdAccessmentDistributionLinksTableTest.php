<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SdAccessmentDistributionLinksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SdAccessmentDistributionLinksTable Test Case
 */
class SdAccessmentDistributionLinksTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SdAccessmentDistributionLinksTable
     */
    public $SdAccessmentDistributionLinks;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sd_accessment_distribution_links',
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
        $config = TableRegistry::getTableLocator()->exists('SdAccessmentDistributionLinks') ? [] : ['className' => SdAccessmentDistributionLinksTable::class];
        $this->SdAccessmentDistributionLinks = TableRegistry::getTableLocator()->get('SdAccessmentDistributionLinks', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SdAccessmentDistributionLinks);

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
