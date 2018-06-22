<?php
/**
 * CakePHP New Relic plugin.
 *
 * @author https://github.com/brunitto
 * @author https://github.com/voycey
 * @author https://github.com/rodrigorm
 * @link https://github.com/brunitto/cakephp-new-relic
 */
namespace NewRelic\Test\Middleware;

use Cake\TestSuite\TestCase;
use NewRelic\Middleware\NameTransactionMiddleware;
use Cake\Http\ServerRequest;
use Cake\Http\Response;
use Cake\Http\MiddlewareQueue;
use Cake\Http\Runner;

/**
 * Test name transaction middleware.
 *
 * @uses TestCase
 */
class NameTransactionMiddlewareTest extends TestCase
{
    /**
     * The test subject.
     *
     * @var NewRelic\Middleware\NameTransactionMiddleware
     */
    public $NameTransactionMiddleware;

    /**
     * A test request.
     *
     * @var Cake\Http\ServerRequest
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
        $this->NameTransactionMiddleware = new NameTransactionMiddleware();
        $this->Request = new ServerRequest();
    }

    /**
     * tearDown method.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->Request = null;
        $this->NameTransactionMiddleware = null;
        parent::tearDown();
    }

    /**
     * Test __invoke method.
     *
     * Assert that the invoke call the next middleware properly.
     *
     * @return void
     */
    public function testInvoke()
    {
        $response = new Response();
        $request = new ServerRequest();

        $middleware = new MiddlewareQueue();
        $middleware->add(new NameTransactionMiddleware());

        $runner = new Runner();

        $mock = $this->getMockBuilder(stdClass::class)
                     ->setMethods(['__invoke'])
                     ->getMock();
        $mock->expects($this->once())
             ->method('__invoke')
             ->with($request, $response, $runner)
             ->willReturn($response);
        $middleware->add($mock);

        $result = $runner->run($middleware, $request, $response);
        $this->assertSame($response, $result);
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
            $this->NameTransactionMiddleware->nameTransaction(
                $this->Request
                    ->withParam('plugin', null)
                    ->withParam('controller', 'TestController')
                    ->withParam('action', 'test')),
            'test-controller/test'
        );

        // Assert the transaction name using plugin/controller/action
        $this->assertEquals(
            $this->NameTransactionMiddleware->nameTransaction(
                $this->Request
                    ->withParam('plugin', 'TestPlugin')
                    ->withParam('controller', 'TestController')
                    ->withParam('action', 'test')),
                'test-plugin/test-controller/test'
        );

        // Assert the transaction name using prefix/controller/action
        $this->assertEquals(
            $this->NameTransactionMiddleware->nameTransaction(
                $this->Request
                    ->withParam('prefix', 'TestPrefix')
                    ->withParam('controller', 'TestController')
                    ->withParam('action', 'test')),
                'test-prefix/test-controller/test'
        );

        // Assert the transaction name using plugin/prefix/controller/action
        $this->assertEquals(
            $this->NameTransactionMiddleware->nameTransaction(
                $this->Request
                    ->withParam('plugin', 'TestPlugin')
                    ->withParam('prefix', 'TestPrefix')
                    ->withParam('controller', 'TestController')
                    ->withParam('action', 'test')),
                'test-plugin/test-prefix/test-controller/test'
        );
    }
}
