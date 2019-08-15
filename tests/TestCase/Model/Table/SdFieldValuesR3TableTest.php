<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SdFieldValuesR3Table;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SdFieldValuesR3Table Test Case
 */
class SdFieldValuesR3TableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SdFieldValuesR3Table
     */
    public $SdFieldValuesR3;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sd_field_values_r3',
        'app.sd_cases',
        'app.sd_fields',
        'app.sd_case_distributions'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('SdFieldValuesR3') ? [] : ['className' => SdFieldValuesR3Table::class];
        $this->SdFieldValuesR3 = TableRegistry::getTableLocator()->get('SdFieldValuesR3', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SdFieldValuesR3);

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
