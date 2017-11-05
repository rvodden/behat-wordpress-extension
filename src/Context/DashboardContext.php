<?php
namespace PaulGibbs\WordpressBehatExtension\Context;

use PaulGibbs\WordpressBehatExtension\PageObject\DashboardPage;

/**
 * Provides step definitions that are specific to the WordPress dashboard (wp-admin).
 */
class DashboardContext extends RawWordpressContext
{
    /**
     * Dashboard page object.
     *
     * @var DashboardPage
     */
    protected $admin_page;

    /**
     * Constructor.
     *
     * @param DashboardPage $admin_page
     */
    public function __construct(DashboardPage $admin_page)
    {
        parent::__construct();

        $this->admin_page = $admin_page;
    }

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
        $this->admin_page->open();
    }

    /**
     * Click a link within the page header tag.
     *
     * Example: When I click on the "Add New" link in the header
     *
     * @When I click on the :link link in the header
     *
     * @param string $link
     */
    public function iClickOnHeaderLink($link)
    {
        $this->admin_page->clickLinkInHeader($link);
    }

    /**
     * Assert the text in the page header tag matches the given string.
     *
     * Example: Then I should be on the "Posts" page
     * Example: Then I should be on the "Posts" screen
     *
     * @Then I should be on the :admin_page (page|screen)
     *
     * @param string $admin_page
     */
    public function iShouldBeOnThePage($admin_page)
    {
        $this->admin_page->assertHasHeader($admin_page);
    }

    /**
     * Go to a given page on the admin menu.
     *
     * In 1.0.0, the regex will simplify to 'I go to the menu "foobar"'.
     *
     * Example: Given I go to menu item "Posts > Add New"
     * Example: Given I go to the menu item "Users"
     * Example: Given I go to the menu "Settings > Reading"
     *
     * @Given I go to (the )menu (item ):item
     *
     * @param string $item
     */
    public function iGoToMenuItem($item)
    {
        $adminMenu = $this->admin_page->getMenu();
        $adminMenu->clickMenuItem($item);
    }

    /**
     * Check the specified notification is on-screen.
     *
     * Example: Then I should see a status message that says "Post published"
     *
     * @Then /^(?:I|they) should see an? (error|status) message that says "([^"]+)"$/
     *
     * @param string $type    Message type. Either "error" or "status".
     * @param string $message Text to search for.
     *
     * @throws \Behat\Mink\Exception\ElementTextException
     */
    public function iShouldSeeMessageThatSays($type, $message)
    {
        $selector = 'div.notice';

        if ($type === 'error') {
            $selector .= '.error';
        } else {
            $selector .= '.updated';
        }

        $this->assertSession()->elementTextContains('css', $selector, $message);
    }
}
