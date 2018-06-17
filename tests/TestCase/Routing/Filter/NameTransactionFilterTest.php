<?php
/**
 * CakePHP New Relic plugin.
 *
 * @author https://github.com/brunitto
 * @author https://github.com/voycey
 * @link https://github.com/brunitto/cakephp-new-relic
 */
namespace NewRelic\Test\Routing\Filter;

use NewRelic\Routing\Filter\NameTransactionFilter;
use Cake\Routing\DispatcherFilter;
use Cake\Http\ServerRequest;
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
     * @var Cake\Http\ServerRequest
     */
    public $ServerRequest;

    /**
     * setUp method.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->NameTransactionFilter = new NameTransactionFilter();
        $this->ServerRequest = new ServerRequest();
    }

    /**
     * tearDown method.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->ServerRequest = null;
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
        $this->assertEquals(
            $this->NameTransactionFilter->nameTransaction(
                $this->ServerRequest
                    ->withParam('plugin', null)
                    ->withParam('controller', 'TestController')
                    ->withParam('action', 'test')),
            'test-controller/test'
        );

        // Assert the transaction name using plugin/controller/action
        $this->assertEquals(
            $this->NameTransactionFilter->nameTransaction(
                $this->ServerRequest
                    ->withParam('plugin', 'TestPlugin')
                    ->withParam('controller', 'TestController')
                    ->withParam('action', 'test')),
                'test-plugin/test-controller/test'
        );

        // Assert the transaction name using prefix/controller/action
        $this->assertEquals(
            $this->NameTransactionFilter->nameTransaction(
                $this->ServerRequest
                    ->withParam('prefix', 'TestPrefix')
                    ->withParam('controller', 'TestController')
                    ->withParam('action', 'test')),
                'test-prefix/test-controller/test'
        );

        // Assert the transaction name using plugin/prefix/controller/action
        $this->assertEquals(
            $this->NameTransactionFilter->nameTransaction(
                $this->ServerRequest
                    ->withParam('plugin', 'TestPlugin')
                    ->withParam('prefix', 'TestPrefix')
                    ->withParam('controller', 'TestController')
                    ->withParam('action', 'test')),
                'test-plugin/test-prefix/test-controller/test'
        );
    }
}
