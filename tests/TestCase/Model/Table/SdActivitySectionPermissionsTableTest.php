<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SdActivitySectionPermissionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SdActivitySectionPermissionsTable Test Case
 */
class SdActivitySectionPermissionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SdActivitySectionPermissionsTable
     */
    public $SdActivitySectionPermissions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sd_activity_section_permissions',
        'app.sd_workflow_activities',
        'app.sd_sections'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('SdActivitySectionPermissions') ? [] : ['className' => SdActivitySectionPermissionsTable::class];
        $this->SdActivitySectionPermissions = TableRegistry::getTableLocator()->get('SdActivitySectionPermissions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SdActivitySectionPermissions);

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
