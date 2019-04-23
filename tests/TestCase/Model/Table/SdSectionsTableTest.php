<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SdSectionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SdSectionsTable Test Case
 */
class SdSectionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SdSectionsTable
     */
    public $SdSections;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sd_sections',
        'app.sd_tabs',
        'app.sd_activity_section_permissions',
        'app.sd_section_sets',
        'app.sd_section_structures',
        'app.sd_section_summaries'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('SdSections') ? [] : ['className' => SdSectionsTable::class];
        $this->SdSections = TableRegistry::getTableLocator()->get('SdSections', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SdSections);

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
