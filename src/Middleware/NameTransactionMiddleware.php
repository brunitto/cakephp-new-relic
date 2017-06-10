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
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Message\ResponseInterface $response The response.
     * @param callable $next The next middleware to call.
     * @return \Psr\Http\Message\ResponseInterface A response.
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
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
     * @param Cake\Network\Network $request The request.
     * @return string
     */
    public function nameTransaction(ServerRequestInterface $request)
    {
        // CakePHP does not include the "prefix" key in request "params" array
        // when prefixes are not being used as it does for the "plugin" key, so
        // this code need custom handling
        if (array_key_exists('prefix', $request->params)) {
            $prefix = $request->params['prefix'];
        } else {
            $prefix = null;
        }

        $plugin = $request->params['plugin'];
        $controller = $request->params['controller'];
        $action = $request->params['action'];

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
