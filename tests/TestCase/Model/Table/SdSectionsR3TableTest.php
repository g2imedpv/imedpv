<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SdSectionsR3Table;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SdSectionsR3Table Test Case
 */
class SdSectionsR3TableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SdSectionsR3Table
     */
    public $SdSectionsR3;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sd_sections_r3',
        'app.sd_tabs'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('SdSectionsR3') ? [] : ['className' => SdSectionsR3Table::class];
        $this->SdSectionsR3 = TableRegistry::getTableLocator()->get('SdSectionsR3', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SdSectionsR3);

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
