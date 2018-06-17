<?php
/**
 * CakePHP New Relic plugin.
 *
 * @author https://github.com/brunitto
 * @author https://github.com/voycey
 * @author https://github.com/rodrigorm
 * @link https://github.com/brunitto/cakephp-new-relic
 */
namespace NewRelic\Middleware;

use Cake\Utility\Inflector;
use Cake\Http\ServerRequest;
use Cake\Http\Client\Response;

/**
 * New Relic name transaction middleware.
 */
class NameTransactionMiddleware
{
    /**
     * __invoke() method.
     *
     * Call the newrelic_name_transaction function when the newrelic extension
     * is loaded.
     *
     * @param Cake\Http\ServerRequest $request The request.
     * @param Cake\Http\Client\Response $response The response.
     * @param callable $next The next middleware to call.
     * @return Cake\Http\Client\Response A response.
     */
    public function __invoke(ServerRequest $request, Response $response, $next)
    {
        if (extension_loaded('newrelic')) {
            newrelic_name_transaction($this->nameTransaction($request));
        }

        return $next($request, $response);
    }

    /**
     * nameTransaction() method.
     *
     * Name the transaction using request data.
     *
     * @param Cake\Http\ServerRequest $request The request.
     * @return string
     */
    public function nameTransaction(ServerRequest $request)
    {
        $prefix = $request->getParam('prefix', null);
        $plugin = $request->getParam('plugin', null);
        $controller = $request->getParam('controller');
        $action = $request->getParam('action');

        $transaction = Inflector::dasherize($controller) . '/' . $action;

        if ($prefix !== null) {
            $transaction = Inflector::dasherize($prefix) . '/' . $transaction;
        }

        if ($plugin !== null) {
            $transaction = Inflector::dasherize($plugin) . '/' . $transaction;
        }

        return $transaction;
    }
}
