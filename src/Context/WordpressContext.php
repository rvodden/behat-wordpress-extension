<?php
namespace PaulGibbs\WordpressBehatExtension\Context;

use InvalidArgumentException;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Exception\ElementTextException;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Hook\Scope\AfterScenarioScope;

use SensioLabs\Behat\PageObjectExtension\Context\PageObjectAware;
use function PaulGibbs\WordpressBehatExtension\Util\isWordpressError;

/**
 * Provides step definitions for a range of common tasks. Recommended for all test suites.
 */
class WordpressContext extends RawWordpressContext implements PageObjectAware
{
    use PageObjectContextTrait;

    /**
     * If database.restore_after_test is set, and scenario is tagged "db", create a database backup.
     *
     * The database will be restored from this backup via maybeRestoreDatabase().
     *
     * @BeforeScenario @db
     *
     * @param BeforeScenarioScope $scope
     */
    public function maybeBackupDatabase(BeforeScenarioScope $scope)
    {
        $db = $this->getWordpressParameter('database');
        if (! $db['restore_after_test']) {
            return;
        }

        /*
         * We need the logic of this method to operate only once, and have access to the Context.
         * Otherwise, we would use the (static) BeforeSuiteScope hook.
         */
        $backup_file = $this->getWordpress()->getSetting('database_backup_file');
        if ($backup_file) {
            return;
        }

        $file = $db['backup_path'] ?? '';

        // If the specified file exists, use it as our backup.
        if (! $file || ! is_file($file) || ! is_readable($file)) {
            // Otherwise, treat it as the (optional) preferred folder to store the backup.
            $file = $this->exportDatabase(['path' => $file]);
        }

        // Note: $file may be either an absolute path, or relative.
        $this->getWordpress()->setSetting('database_backup_file', $file);
    }

    /**
     * When using the Selenium driver, position the admin bar to the top of the page, not fixed to the screen.
     * Otherwise the admin toolbar can hide clickable elements.
     *
     * @BeforeStep
     */
    public function fixToolbar()
    {
        $driver = $this->getSession()->getDriver();
        if (! $driver instanceof \Behat\Mink\Driver\Selenium2Driver || ! $driver->getWebDriverSession()) {
            return;
        }

        try {
            $this->getSession()->getDriver()->executeScript(
                'if (document.getElementById("wpadminbar")) {
                    document.getElementById("wpadminbar").style.position="absolute";
                    if (document.getElementsByTagName("body")[0].className.match(/wp-admin/)) {
                        document.getElementById("wpadminbar").style.top="-32px";
                    }
                };'
            );
        } catch (\Exception $e) {
            /*
             * If a browser is not open, then Selenium2Driver::executeScript() will throw an exception.
             * In this case, our toolbar workaround obviously isn't required, so fail quietly.
             */
        }
    }

    /**
     * Clear object cache.
     *
     * @AfterScenario
     */
    public function clearCache()
    {
        parent::clearCache();
    }

    /**
     * Clear Mink's browser environment.
     *
     * @AfterScenario
     */
    public function resetBrowser()
    {
        parent::resetBrowser();
    }

    /**
     * If database.restore_after_test is set, and scenario is tagged "db", restore the database from a backup.
     *
     * The database will be restored from a backup made via maybeBackupDatabase().
     *
     * @AfterScenario @db
     *
     * @param AfterScenarioScope $scope
     */
     public function maybeRestoreDatabase(AfterScenarioScope $scope)
     {
        $db = $this->getWordpressParameter('database');
        if (! $db['restore_after_test']) {
            return;
        }

        $file = $this->getWordpress()->getSetting('database_backup_file');
        if (! $file) {
            return;
        }

        $this->importDatabase(['path' => $file]);
     }

    /*
     * Step definitions lurk beyond.
     */

    /**
     * Open the dashboard.
     *
     * Example: Given I am on the dashboard
     * Example: Given I am in wp-admin
     * Example: When I go to the dashboard
     * Example: When I go to wp-admin
     *
     * @Given /^(?:I am|they are) on the dashboard/
     * @Given /^(?:I am|they are) in wp-admin/
     * @When /^(?:I|they) go to the dashboard/
     * @When /^(?:I|they) go to wp-admin/
     */
    public function iAmOnDashboard()
    {
        $this->visitPath('wp-admin/');
    }

    /**
     * Searches for a term using the toolbar search field
     *
     * Example: When I search for "Hello World" in the toolbar
     *
     * @When I search for :search in the toolbar
     */
    public function iSearchUsingTheToolbar($search)
    {
        $this->getElement('Toolbar')->search($search);
    }

    /**
     * Clicks the specified link in the toolbar.
     *
     * Example: Then I should see "Howdy, admin" in the toolbar
     *
     * @Then I should see :text in the toolbar
     */
    public function iShouldSeeTextInToolbar($text)
    {
        $toolbar = $this->getElement('Toolbar');
        $actual = $toolbar->getText();
        $regex = '/' . preg_quote($text, '/') . '/ui';

        if (! preg_match($regex, $actual)) {
            $message = sprintf('The text "%s" was not found in the toolbar', $text);
            throw new ElementTextException($message, $this->getSession()->getDriver(), $toolbar);
        }
    }

    /**
     * Clicks the specified link in the toolbar.
     *
     * Example: When I follow the toolbar link "New > Page"
     * Example: When I follow the toolbar link "Updates"
     * Example: When I follow the toolbar link "Howdy, admin > Edit My Profile"
     *
     * @When I follow the toolbar link :link
     */
    public function iFollowTheToolbarLink($link)
    {
        $this->getElement('Toolbar')->clickToolbarLink($link);
    }
}
