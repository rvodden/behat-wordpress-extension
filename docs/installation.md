# WordHat

!!! summary
    Do you know how to use Composer? tl;dr?

    Require `paulgibbs/behat-wordpress-extension`, copy its `behat.yml.dist` into your project (minus `.dist`), install <a href="https://wp-cli.org/">WP-CLI</a> globally, and run `vendor/bin/behat --init`.

!!! warning
    Check that all of the [requirements](#requirements) are met.


## Installation

<ol>

<li>Create a folder for your tests:
<pre><code>mkdir projectfolder
cd projectfolder</code></pre>
</li>

<li>Tell <a href="https://getcomposer.org/">Composer</a> to install WordHat. To do this conveniently, run:
<pre><code># WordHat.
php composer.phar require --dev paulgibbs/behat-wordpress-extension

# These will help you get started.
php composer.phar require --dev behat/mink-goutte-driver
</code></pre>

This will create a <code>composer.json</code> file in your project, and download WordHat.
</li>

<li>WordHat comes with a sample configuration file to help you set up the test environment. Copy it into your project folder and name it `behat.yml`:
<pre><code>cp vendor/paulgibbs/behat-wordpress-extension/behat.yml.dist behat.yml
</code></pre>

Edit that file and change the `base_url` setting to point at the website that you intend to test.
</li>

<li>Initialise Behat with:
<pre><code>vendor/bin/behat --init
</code></pre>

This will generate a `features/` folder for your [features](http://docs.behat.org/en/latest/user_guide/features_scenarios.html#features), and a new [context](http://docs.behat.org/en/latest/user_guide/context.html) in `features/bootstrap/`. The latter is aware of both the WordPress and [Mink](https://github.com/Behat/MinkExtension) extensions, so you will be able to take advantage of them as you build your own custom [step definitions or hooks](http://docs.behat.org/en/latest/user_guide/writing_scenarios.html).
</li>

</ol>


## Usage

To confirm that everything is set up correctly, run:

```Shell
vendor/bin/behat -dl
```

If everything worked, you will see a list of steps like the following (but much longer):

```Gherkin
Given I am an anonymous user
Given I am not logged in
Given I am logged in as a user with the :role role(s)
Given I am logged in as :name
```

Now you are ready to start writing your tests! If you are new to Behat, you might want to review its [quick start](http://behat.org/en/latest/quick_start.html#example) documentation. Good luck, and happy testing!

## Requirements

Package                              | Minimum required version
------------------------------------ | ------------------------
[Composer](https://getcomposer.org/) | *
[PHP](https://php.net/)              | >= 5.6
[WordPress](https://wordpress.org/)  | >= 4.7


### Suggested extras

Package                              | Minimum required version
------------------------------------ | ------------------------
[Selenium standalone](http://docs.seleniumhq.org/download/)[^1] | >= 3.0.1
[WP-CLI](https://wp-cli.org/)[^2]                               | >= 0.24.0

[^1]:
    Recommended for testing <a href="http://mink.behat.org/en/latest/guides/drivers.html" id="SEL">websites that require Javascript</a>. Requires the [Mink Selenium2 driver](https://packagist.org/packages/behat/mink-selenium2-driver) in your project.

[^2]:
    The WP-CLI executable *must* be named `wp` and be within your system's <a href="https://en.wikipedia.org/wiki/PATH_(variable)" id="WP-CLI">$PATH</a>.
