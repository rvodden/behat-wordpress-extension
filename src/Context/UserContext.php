<?php
namespace PaulGibbs\WordpressBehatExtension\Context;

use Behat\Gherkin\Node\TableNode;
use RuntimeException;

/**
 * Provides step definitions for all things relating to users.
 */
class UserContext extends RawWordpressContext
{

    /**
     * Add specified user accounts.
     *
     * Example: Given there are users:
     * | user_login | user_pass | user_email | role |
     * | admin | admin | admin@example.com | administrator |
     *
     * @Given /^(?:there are|there is a) users?:/
     *
     * @param TableNode $users
     */
    public function thereAreUsers(TableNode $users)
    {
        $params = $this->getWordpressParameters();
        foreach ($users->getHash() as $user) {
            try {
                print "there are users\n";
                $this->getUserByLogin($user['user_login']);
                /*
                 * the user exists if we've not thrown an exception.
                 * we should write some code here to update the
                 * parameters
                 * to match the requirements of the given statement
                 */
                $params['users'][$user['role']] = array(
                    'username' => $user['user_login'],
                    'password' => $user['user_pass'] // this assumes the
                                                         // required password is right.
                                                         // we don't set it
                );
            } catch (\UnexpectedValueException $uve) {
                // this means that the user doesn't exist and we
                // should create it
                $this->createUser($user['user_login'], $user['user_email'], $user);
                // Store new users by username, not by role (unlike
                // what the docs say).
                $id = strtolower($user['user_login']);
                $params['users']['role'] = array(
                    'username' => $user['user_login'],
                    'password' => $user['user_pass']
                );
            }
        }
        $this->setWordpressParameters($params);
    }

    /**
     * Add user account, and go to their author archive page.
     *
     * Example: Given I am viewing an author archive:
     * | user_login | user_pass | user_email | role |
     * | admin | admin | admin@example.com | administrator |
     *
     * @Given /^(?:I am|they are) viewing an author archive:/
     *
     * @param TableNode $user_data
     */
    public function iAmViewingAuthorArchive(TableNode $user_data)
    {
        $params = $this->getWordpressParameters();
        // Create user.
        $user = $user_data->getHash();
        $new_user = $this->createUser($user['user_login'], $user['user_email'], $user);
        // Store new users by username, not by role (unlike what the
        // docs say).
        $id = strtolower($user['user_login']);
        $params['users'][$id] = array(
            'username' => $user['user_login'],
            'password' => $user['user_pass']
        );
        $this->setWordpressParameters($params);
        // Navigate to archive.
        $this->visitPath(sprintf($params['permalinks']['author_archive'], $new_user['slug']));
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
     * Log user in, by specifying role.
     *
     * Example: Given I am logged in as an admin
     *
     * @Given /^(?:I am|they are) logged in as a(?:n) ([a-zA-Z ]+)$/
     *
     * @param string $role
     *
     * @throws \RuntimeException
     */
    public function iAmLoggedInAsA($role)
    {
        $role = strtolower($role);
        $users = $this->getWordpressParameter('users');
        if ($users === null || empty($users[$role])) {
            throw new RuntimeException("User details for role \"{$role}\" not found.");
        }
        $this->logIn($users[$role]['username'], $users[$role]['password']);
    }

    /**
     * Log user in, by specifying user.
     *
     * Example: Given I am logged in as david
     *
     * @Given /^(?:I am|they are) logged in as ([a-zA-Z]+)$/
     *
     * @param string $user
     *
     * @throws \RuntimeException
     */
    public function iAmLoggedInAs($login)
    {
        $login = strtolower($login);
        $users = $this->getWordpressParameter('users');
        if ($users === null) {
            throw new RuntimeException("Details for user \"{$login}\" not found.");
        }
        
        foreach ($users as $role => $user) {
            if ($user['username'] == $login) {
                $this->logIn($users[$role]['username'], $users[$role]['password']);
                return;
            }
        }

        throw new RuntimeException("Details for user \"{$login}\" not found.");
    }
}
