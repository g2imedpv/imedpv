<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SdContactsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SdContactsTable Test Case
 */
class SdContactsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SdContactsTable
     */
    public $SdContacts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sd_contacts'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('SdContacts') ? [] : ['className' => SdContactsTable::class];
        $this->SdContacts = TableRegistry::getTableLocator()->get('SdContacts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SdContacts);

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
}
