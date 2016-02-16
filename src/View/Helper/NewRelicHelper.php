<?php
/**
 * CakePHP New Relic plugin.
 *
 * @author Bruno Moyle <brunitto@gmail.com>
 * @link https://github.com/brunitto/cakephp-new-relic
 */
namespace NewRelic\View\Helper;

use Cake\View\Helper;

/**
 * New Relic helper.
 *
 * @uses Helper
 */
class NewRelicHelper extends Helper
{
    /**
     * getBrowserTimingHeader() method.
     *
     * Renders the browser timing header.
     *
     * @return string
     */
    public function getBrowserTimingHeader()
    {
        if (extension_loaded('newrelic')) {
            return newrelic_get_browser_timing_header();
        }
    }

    /**
     * getBrowserTimingFooter() method.
     *
     * Renders the browser timing footer.
     *
     * @return string
     */
    public function getBrowserTimingFooter()
    {
        if (extension_loaded('newrelic')) {
            return newrelic_get_browser_timing_footer();
        }
    }
}
