<?php
namespace PaulGibbs\WordpressBehatExtension\Context;

use Behat\Mink\Exception\ElementTextException;

/**
 * Provides step definitions for the WordPress Toolbar.
 */
class ToolbarContext extends RawWordpressContext
{
    /**
     * Searches for a term using the toolbar search field
     *
     * Example: When I search for "Hello World" in the toolbar
     *
     * @When I search for :search in the toolbar
     *
     * @param $search
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
     *
     * @param string $text
     *
     * @throws ElementTextException
     */
    public function iShouldSeeTextInToolbar($text)
    {
        $toolbar = $this->getElement('Toolbar');
        $actual  = $toolbar->getText();
        $regex   = '/' . preg_quote($text, '/') . '/ui';

        if (! preg_match($regex, $actual)) {
            $message = sprintf('The text "%s" was not found in the toolbar', $text);
            throw new ElementTextException($message, $this->getSession()->getDriver(), $toolbar);
        }
    }
    
    /**
     * Checks for the authenticated user showin in the toolbar.
     * 
     * Example: Then the toolbar should show I am authenticated as admin
     * 
     * @Then /^Then the toolbar should show I am authenticated as (.+)$/
     * 
     * @param string $username
     * 
     * @throws Exception
     */
    public function theUsernameShouldBe($username)
    {
        $authenticated_user = $this->admin_page->getAuthenticatedUserText();
        
        if ($username != $authenticated_user) {
            throw new ElementTextException('Toolbar shows authenticated user is ' . $authenticated_user . ' not ' . $username);
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
     *
     * @param string $link
     */
    public function iFollowTheToolbarLink($link)
    {
        $this->getElement('Toolbar')->clickToolbarLink($link);
    }
}
