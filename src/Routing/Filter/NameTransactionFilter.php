<?php
/**
 * CakePHP New Relic plugin.
 *
 * @author https://github.com/brunitto
 * @author https://github.com/voycey
 * @link https://github.com/brunitto/cakephp-new-relic
 */
namespace NewRelic\Routing\Filter;

use Cake\Event\Event;
use Cake\Routing\DispatcherFilter;
use Cake\Http\ServerRequest;
use Cake\Utility\Inflector;

/**
 * New Relic name transaction dispatcher filter.
 *
 * @uses DispatcherFilter
 */
class NameTransactionFilter extends DispatcherFilter
{
    /**
     * beforeDispatch() method.
     *
     * Call the newrelic_name_transaction function when the newrelic extension
     * is loaded.
     *
     * @param Cake\Event\Event $event The event.
     * @return void
     */
    public function beforeDispatch(Event $event)
    {
        if (extension_loaded('newrelic')) {
            $request = $event->data['request'];
            newrelic_name_transaction($this->nameTransaction($request));
        }
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
