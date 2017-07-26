# Overview

WordHat provides WordPress-specific functionality for common testing scenarios that are specific to WordPress sites. We do this by providing a range of [Behat Contexts](/getting-started/behat-intro.md#contexts) that provide useful Step Definitions.

For convenience, our `behat.yml.dist` configuration template loads all of our Contexts by default, though Behat does support [more complex configurations](http://behat.org/en/latest/user_guide/configuration/suites.html) for advanced use cases.

To find out which Step Definitions are available for your tests, consult this documentation or run `vendor/bin/behat -dl` in your terminal.

# Debug

# Content
# Site
# User

# Dashboard
# EditPost
# Widget

Feature                                  | WP-CLI                     | WordPress PHP | Blackbox
---------------------------------------- | -------------------------- | ------------- | --------
Posts and comments.                      | Yes                        | Yes           | No
Terms for taxonomy.                      | Yes                        | Yes           | No
Manage users.                            | Yes                        | Yes           | No
Manage plugins.                          | Yes                        | Yes           | No
Switch theme.                            | Yes                        | Yes           | No
Clear cache.                             | Yes                        | Yes           | No
Database import/export.                  | Yes                        | No            | No
Run tests and site on different servers. | Yes[^1] | No            | Yes
Database transactions.                   | No                         | Yes           | No

[^1]:
    WP-CLI <a href="https://wp-cli.org/blog/version-0.24.0.html#but-wait-whats-the-ssh-in-there" id="WP-CLI">supports SSH connections</a> to remote WordPress sites.
