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

* CakePHP 3.6 or greater
* PHP 7.1 or greater
* New Relic PHP extension

Development requirements:

* PHPUnit 7.1 or greater

## Installation

Install the plugin using [Composer](https://getcomposer.org/), executing the
following command in your project's root directory (where the `composer.json`
file is located.)

    composer require brunitto/cakephp-new-relic

## Usage

### Enable name transaction

#### Using Dispatcher

Add the Dispatcher Filter to the `bootstrap.php` file:

    // New Relic name transaction dispatcher filter
    DispatcherFactory::add('NewRelic.NameTransaction');

#### Using Middleware

Add the Middleware to the `src/Application.php` file after the `RoutingMiddleware`:

    $middlewareQueue
        ->add(new RoutingMiddleware($this))
        ->add(new NameTransactionMiddleware());

### Enable browser timing

Load the plugin helper within `src/View/AppView.php`, add the following line
within the `initialize()` method:

    $this->loadHelper('NewRelic.NewRelic');

Like this:

    public function initialize()
    {
        parent::initialize();
        $this->loadHelper('NewRelic.NewRelic');
    }

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

## Middleware support

This plugin is ready for Middleware, as defined in:

[Middleware](https://book.cakephp.org/3.0/en/controllers/middleware.html)

## Development guidelines

### Install dependencies

Install using `composer` program:

    $ composer install --ignore-platform-reqs

### Running tests

Run using `phpunit` program:

    $ vendor/bin/phpunit tests --color

### Releasing a new version

1. Plan a release on GitHub, using Semantic Versioning, defining which issues
will be on this release
2. Checkout to `development` and merge `master`, starting from the last stable
version
3. Work in `development`, commiting and pushing to `origin` when you have
working code. This will trigger Travis CI and run tests as soon as possible. If
you fix an issue, use the `, fix #N` suffix in commit messages, where #N is the
issue identifier. This will close issues on GitHub
4. When the `development` is ready to go, merge in `master` and creates two
soft tags: the version tag `1.2.3` and the `stable` tag. The version tag is
used by GitHub and Packagist to define a new release and a stable release
5. Push `master` to `origin` including the tags and wait for Travis CI to
run and the tests to pass

## Issues

If you have some problem with this plugin, please open an issue on:

[https://github.com/brunitto/cakephp-new-relic/issues](https://github.com/brunitto/cakephp-new-relic/issues)

## Contribute

If you'd like to contribute, you can fork the project, add features and send
pull requests in the official repository:

[https://github.com/brunitto/cakephp-new-relic](https://github.com/brunitto/cakephp-new-relic)
