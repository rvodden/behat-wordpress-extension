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
