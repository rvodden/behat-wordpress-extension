# Overview

WordHat provides WordPress-specific functionality for common testing scenarios that are specific to WordPress sites. We do this by providing a range of [Behat Contexts](/getting-started/behat-intro.md#contexts) that provide useful Step Definitions.

For convenience, our `behat.yml.dist` configuration template loads all of our Contexts by default, though Behat does support [more complex configuration](http://behat.org/en/latest/user_guide/configuration/suites.html) for advanced use cases.

To find out which Step Definitions are available for your tests, consult this documentation or run `vendor/bin/behat -dl` in your terminal.


## Drivers

The `Given` and `When` steps in a [Behat Scenario](/getting-started/behat-intro.md#scenarios) configure a WordPress into a known state for reliable testing. WordHat abstracts this communication between WordPress and itself into a Drivers system. Two Drivers are currently provided: WP-CLI (the default), and WP-PHP.

!!! important
    WordHat only uses Drivers to configure a WordPress into a known state.

    Your actual tests are run in a web browser, via the [Mink](http://mink.behat.org/en/latest/) package.

To configure WordHat to use a specific Driver, set [`default_driver`](/configuration/settings.md) in `behat.yml.dist`.

### WP-CLI

The WP-CLI Driver uses [WP-CLI](https://wp-cli.org) to connect to WordPress.
This is the default and recommended Driver, and allows you to [run your tests and your WordPress site on different servers](https://make.wordpress.org/cli/handbook/running-commands-remotely/).

### WP-PHP

The WP-PHP Driver loads WordPress by bootstrapping it directly. This approach was taken from WordPress' [PHPUnit integration test framework](https://make.wordpress.org/core/handbook/testing/automated-testing/phpunit/). You have to run your tests directly on your WordPress server.


## Contexts
### Content

WordHat provides a full range of Step Definitions for managing content (Posts, Pages, and Custom Content Types) and taxonomy terms.

### Site

WordHat provides Step Definitions for activating/deactivating plugins, switching themes, and clearing the object cache.

Step Definitions are also provided for importing/exporting the database. This is supported by all Drivers but, for reliability, we strongly recommend using the WP-CLI Driver.

### User

WordHat provides a full range of Step Definitions for managing users.

### wp-admin
#### Dashboard

[Learn more <span class="screen-reader-text">about Step Definitions for interacting with users inside the WordPress dashboard.</span>](#)

#### Widget

[Learn more <span class="screen-reader-text">about Step Definitions for interacting with widgets inside the WordPress dashboard.</span>](#)

### Debug

WordHat provides Step Definitions that help you debug Scenarios during development.
