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
use Cake\Network\Request;
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
     * @var Cake\Network\Request
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
        // Assert the transaction name using controller/action
        $this->Request->params['plugin'] = null;
        $this->Request->params['prefix'] = null;
        $this->Request->params['controller'] = 'TestController';
        $this->Request->params['action'] = 'test';
        $this->assertEquals(
            $this->NameTransactionFilter->nameTransaction($this->Request),
            'test-controller/test'
        );

        // Assert the transaction name using plugin/controller/action
        $this->Request->params['plugin'] = 'TestPlugin';
        $this->Request->params['prefix'] = null;
        $this->Request->params['controller'] = 'TestController';
        $this->Request->params['action'] = 'test';
        $this->assertEquals(
            $this->NameTransactionFilter->nameTransaction($this->Request),
            'test-plugin/test-controller/test'
        );

        // Assert the transaction name using prefix/controller/action
        $this->Request->params['plugin'] = null;
        $this->Request->params['prefix'] = 'TestPrefix';
        $this->Request->params['controller'] = 'TestController';
        $this->Request->params['action'] = 'test';
        $this->assertEquals(
            $this->NameTransactionFilter->nameTransaction($this->Request),
            'test-prefix/test-controller/test'
        );

        // Assert the transaction name using plugin/prefix/controller/action
        $this->Request->params['plugin'] = 'TestPlugin';
        $this->Request->params['prefix'] = 'TestPrefix';
        $this->Request->params['controller'] = 'TestController';
        $this->Request->params['action'] = 'test';
        $this->assertEquals(
            $this->NameTransactionFilter->nameTransaction($this->Request),
            'test-plugin/test-prefix/test-controller/test'
        );
    }
}
