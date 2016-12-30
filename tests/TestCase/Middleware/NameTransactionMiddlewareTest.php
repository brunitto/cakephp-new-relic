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
use Zend\Diactoros\Request;
use Zend\Diactoros\ServerRequestFactory;

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
     * @var Zend\Diactoros\Request
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
        $this->Request = ServerRequestFactory::fromGlobals(['REQUEST_URI' => '/testpath']);
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
            $this->NameTransactionMiddleware->nameTransaction($this->Request),
            'test-controller/test'
        );

        // Assert the transaction name using plugin/controller/action
        $this->Request->params['plugin'] = 'TestPlugin';
        $this->Request->params['prefix'] = null;
        $this->Request->params['controller'] = 'TestController';
        $this->Request->params['action'] = 'test';
        $this->assertEquals(
            $this->NameTransactionMiddleware->nameTransaction($this->Request),
            'test-plugin/test-controller/test'
        );

        // Assert the transaction name using prefix/controller/action
        $this->Request->params['plugin'] = null;
        $this->Request->params['prefix'] = 'TestPrefix';
        $this->Request->params['controller'] = 'TestController';
        $this->Request->params['action'] = 'test';
        $this->assertEquals(
            $this->NameTransactionMiddleware->nameTransaction($this->Request),
            'test-prefix/test-controller/test'
        );

        // Assert the transaction name using plugin/prefix/controller/action
        $this->Request->params['plugin'] = 'TestPlugin';
        $this->Request->params['prefix'] = 'TestPrefix';
        $this->Request->params['controller'] = 'TestController';
        $this->Request->params['action'] = 'test';
        $this->assertEquals(
            $this->NameTransactionMiddleware->nameTransaction($this->Request),
            'test-plugin/test-prefix/test-controller/test'
        );
    }
}
