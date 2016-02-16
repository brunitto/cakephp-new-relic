# CakePHP NewRelic

CakePHP New Relic plugin.

## Build status

[![Build Status](https://travis-ci.org/brunitto/cakephp-new-relic.svg?branch=master)](https://travis-ci.org/brunitto/cakephp-new-relic)

## Overview

This plugin extends the [CakePHP Request cycle](http://book.cakephp.org/3.0/en/intro.html#cakephp-request-cycle), adding
a specialized [Dispatcher Filter](book.cakephp.org/3.0/en/development/dispatch-filters.html) that adds
support for New Relic. The current version supports:

* Name transactions
* Browser timing

For more information, visit:

[PHP Frameworks: Integrating support for New Relic](https://docs.newrelic.com/docs/agents/php-agent/frameworks-libraries/php-frameworks-integrating-support-new-relic)

## Requirements

This plugin has the following requirements:

* CakePHP 3.2 or greater
* PHP 5.9 or greater
* New Relic PHP extension

## Installation

Install the plugin using [Composer](https://getcomposer.org/), executing the
following command in your project's root directory (where the `composer.json`
file is located.)

    composer require brunitto/cakephp-new-relic

## Usage

### Enable name transaction

Add the Dispatcher Filter to the `bootstrap.php` file:

    // New Relic name transaction dispatcher filter
    DispatcherFactory::add('NewRelic.NameTransaction');

### Enable browser timing

In your [Layouts](http://book.cakephp.org/3.0/en/views.html#layouts) files, use
the `NewRelic` helper methods to get the browser timing header and footer.

The header goes right before the `</head>` element:

    <head>
    ...
    <!-- NEW RELIC BROWSER TIMING HEADER -->
    <?= $this->NewRelic->getBrowserTimingHeader() ?>
    </head>

And the footer goes right before the `</body>` element:

    <body>
    ...
    <!-- NEW RELIC BROWSER TIMING FOOTER -->
    <?= $this->NewRelic->getBrowserTimingFooter() ?>
    </body>

## Reporting issues

If you have some problem with this plugin, please open an issue on:

https://github.com/brunitto/cakephp-new-relic/issues

## Contribute

If you'd like to contribute, you can fork the project, add features and send
pull requests in the official repository:

https://github.com/brunitto/cakephp-new-relic
