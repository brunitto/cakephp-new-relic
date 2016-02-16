<?php
/**
 * CakePHP New Relic plugin.
 *
 * @author Bruno Moyle <brunitto@gmail.com>
 * @link https://github.com/brunitto/cakephp-new-relic
 */
namespace NewRelic\Routing\Filter;

use Cake\Event\Event;
use Cake\Routing\DispatcherFilter;
use Cake\Network\Http\Request;

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
     * @param Cake\Network\Http\Network $request The request.
     * @return string
     */
    public function nameTransaction(Request $request)
    {
        $plugin = $request->params['plugin'];
        $controller = $request->params['controller'];
        $action = $request->params['action'];

        if ($plugin === null) {
            $transaction =  $controller . '/' . $action;
        } else {
            $transaction =  $plugin . '/' . $controller . '/' . $action;
        }

        return $transaction;
    }
}
