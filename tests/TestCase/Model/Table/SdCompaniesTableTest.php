<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SdCompaniesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SdCompaniesTable Test Case
 */
class SdCompaniesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SdCompaniesTable
     */
    public $SdCompanies;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sd_companies',
        'app.sd_user_types',
        'app.sd_users',
        'app.sd_workflows'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('SdCompanies') ? [] : ['className' => SdCompaniesTable::class];
        $this->SdCompanies = TableRegistry::getTableLocator()->get('SdCompanies', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SdCompanies);

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
