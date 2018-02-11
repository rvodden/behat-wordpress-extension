<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Context;

use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Exception\ExpectationException;
use RuntimeException;

/**
 * Provides step definitions for all things relating to users.
 */
class UserContext extends RawWordpressContext
{
    use Traits\UserAwareContextTrait, Traits\CacheAwareContextTrait;

    /**
     * Add specified user accounts.
     *
     * Example: Given there are users:
     *     | user_login | user_pass | user_email        | role          |
     *     | admin      | admin     | admin@example.com | administrator |
     *
     * @Given /^(?:there are|there is a) users?:/
     *
     * @param TableNode $users
     */
    public function thereAreUsers(TableNode $users)
    {
        $params = $this->getWordpressParameters();

        foreach ($users->getHash() as $user) {
            $this->createUser($user['user_login'], $user['user_email'], $user);

            // Store new users by username, not by role (unlike what the docs say).
            $user_id = strtolower($user['user_login']);
            $params['users'][$user_id] = array(
                'username' => $user['user_login'],
                'password' => $user['user_pass'],
            );
        }

        $this->setWordpressParameters($params);
    }

    /**
     * Delete the specified user account.
     *
     * Example: When I delete the "test" user account
     *
     * @When I delete the :user_login user account
     *
     * @param string $user_login
     */
    public function iDeleteTheUserAccount(string $user_login)
    {
        $this->deleteUser($this->getUserIdFromLogin($user_login));
    }

    /**
     * Go to a user's author archive page.
     *
     * Example: Given I am viewing posts published by Admin
     * Example: When I am viewing posts published by Admin
     *
     * @When I am viewing posts published by :user
     *
     * @param string $username
     */
    public function iAmViewingAuthorArchive(string $username)
    {
        $this->visitPath(sprintf(
            $this->getWordpressParameters()['permalinks']['author_archive'],
            $this->getUserDataFromUsername('user_nicename', $username)
        ));
    }

    /**
     * Log user out.
     *
     * Example: Given I am an anonymous user
     * Example: When I log out
     *
     * @Given /^(?:I am|they are) an anonymous user/
     * @When I log out
     */
    public function iAmAnonymousUser()
    {
        $this->logOut();
    }

    /**
     * Log user in.
     *
     * Example: Given I am logged in as an admin
     *
     * @Given /^(?:I am|they are) logged in as (?:a |an )?(.+)$/
     *
     * @param string $role
     *
     * @throws \RuntimeException
     */
    public function iAmLoggedInAs(string $role)
    {
        $role  = strtolower($role);
        $users = $this->getWordpressParameter('users');

        if ($users === null || empty($users[$role])) {
            throw new RuntimeException("User details for role \"{$role}\" not found.");
        }

        $this->logIn($users[$role]['username'], $users[$role]['password']);
    }

    /**
     * Try to log user in, but expect failure.
     *
     * Example: Then I should not be able to log in as an editor
     *
     * @Then /^(?:I|they) should not be able to log in as (?:a |an )?(.+)$/
     *
     * @param string $role
     *
     * @throws ExpectationException
     */
    public function iShouldNotBeAbleToLogInAs($role)
    {
        try {
            $this->iAmLoggedInAs($role);
        } catch (ExpectationException $e) {
            // Expectation fulfilled.
            return;
        } catch (RuntimeException $e) {
            // Expectation fulfilled.
            return;
        }

        throw new ExpectationException(
            sprintf(
                'The user "%s" was logged-in succesfully. This should not have happened.',
                $role
            ),
            $this->getSession()->getDriver()
        );
    }
}
