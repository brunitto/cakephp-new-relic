<?php
/**
 * CakePHP New Relic plugin.
 *
 * @author Bruno Moyle <brunitto@gmail.com>
 * @link https://github.com/brunitto/cakephp-new-relic
 */
namespace NewRelic\Test\Routing\Filter;

use NewRelic\Routing\Filter\NameTransactionFilter;
use Cake\Routing\DispatcherFilter;
use Cake\Network\Http\Request;
use Cake\TestSuite\TestCase;

/**
 * Test name transaction dispatcher filter.
 *
 * @uses TestCase
 */
class NameTransactionsFilterTest extends TestCase
{
    /**
     * The test subject.
     *
     * @var NewRelic\Routing\Filter\NameTransactionFilter
     */
    public $NameTransactionFilter;

    /**
     * A test request.
     *
     * @var Cake\Network\Http\Request
     */
    public $Request;

    /**
     * setUp method.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->NameTransactionFilter = new NameTransactionFilter();
        $this->Request = new Request();
    }

    /**
     * tearDown method.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->Request = null;
        $this->NameTransactionFilter = null;
        parent::tearDown();
    }

    /**
     * Test nameTransaction method.
     *
     * Assert that the transaction name is obtained from a request, including
     * requests with or without plugin.
     *
     * @return void
     */
    public function testNameTransaction()
    {
        // Assert the transaction name with a plugin
        $this->Request->params['plugin'] = 'NewRelic';
        $this->Request->params['controller'] = 'Transactions';
        $this->Request->params['action'] = 'name';
        $this->assertEquals(
            $this->NameTransactionFilter->nameTransaction($this->Request),
            'NewRelic/Transactions/name'
        );

        // Assert the transaction name without a plugin
        $this->Request->params['plugin'] = null;
        $this->assertEquals(
            $this->NameTransactionFilter->nameTransaction($this->Request),
            'Transactions/name'
        );
    }
}
