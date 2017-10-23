<?php
namespace PaulGibbs\WordpressBehatExtension\Context\Traits;

use Behat\Mink\Exception\DriverException;
use Behat\Mink\Exception\ExpectationException;
use Behat\Mink\Exception\UnsupportedDriverActionException;

/**
 * Provides driver agnostic logic (helper methods) relating to users.
 */
trait UserAwareContextTrait
{
    use BaseAwarenessTrait;

    /**
     * Log in the user.
     *
     * @param string $username
     * @param string $password
     * @param string $redirect_to Optional. After succesful log in, redirect browser to this path. Default = "/".
     *
     * @throws ExpectationException
     */
    public function logIn($username, $password, $redirectTo = '/')
    {
        if ($this->loggedIn()) {
            $this->logOut();
        }

        $this->visitPath('wp-login.php?redirect_to=' . urlencode($this->locatePath($redirectTo)));
        $page = $this->getSession()->getPage();

        $node = $page->findField('user_login');
        try {
            $node->focus();
        } catch (UnsupportedDriverActionException $e) {
            // This will fail for GoutteDriver but neither is it necessary.
        }
        $node->setValue('');
        $node->setValue($username);

        $node = $page->findField('user_pass');
        try {
            $node->focus();
        } catch (UnsupportedDriverActionException $e) {
            // This will fail for GoutteDriver but neither is it necessary.
        }
        $node->setValue('');
        $node->setValue($password);

        $page->findButton('wp-submit')->click();

        if (! $this->loggedIn()) {
            throw new ExpectationException('The user could not be logged-in.', $this->getSession()->getDriver());
        }
    }

    /**
     * Log the current user out.
     *
     * @throws \RuntimeException
     */
    public function logOut()
    {
        $this->getElement('Toolbar')->logOut();
    }

    /**
     * Determine if the current user is logged in or not.
     *
     * @return bool
     */
    public function loggedIn()
    {
        $page = $this->getSession()->getPage();

        // Look for a selector to determine if the user is logged in.
        try {
            return $page->has('css', 'body.logged-in');
        } catch (DriverException $e) {
            // This may fail if the user has not loaded any site yet.
        }

        return false;
    }


    /**
     * Create a user.
     *
     * @param string $userLogin  User login name.
     * @param string $userEmail  User email address.
     * @param array  $args        Optional. Extra parameters to pass to WordPress.
     *
     * @return array {
     *         @type int $id User ID.
     *         @type string $slug User slug (nicename).
     *         }
     */
    public function createUser($userLogin, $userEmail, $args = [])
    {
        $args['user_email'] = $userEmail;
        $args['user_login'] = $userLogin;

        $user = $this->getDriver()->user->create($args);

        return array(
            'id'   => $user->ID,
            'slug' => $user->user_nicename
        );
    }

    /**
     * Get a user's ID from their username.
     *
     * @param string $username The username of the user to get the ID of.
     *
     * @throws \UnexpectedValueException If provided data is invalid
     *
     * @return int ID of the user.
     */
    public function getUserIdFromLogin($username)
    {
        return $this->getDriver()->user->get($username, ['by' => 'login'])->ID;
    }

    /**
     * Delete a user.
     *
     * @param int   $userId   ID of user to delete.
     * @param array $args Optional. Extra parameters to pass to WordPress.
     */
    public function deleteUser($userId, $args = [])
    {
        $this->getDriver()->user->delete($userId, $args);
    }

    /**
     * Get a piece of user data from their username.
     *
     * @param string $data     The user data to return (the name of a column from the WP_Users table).
     * @param string $username The username of the user to fetch a property from.
     *
     * @throws \UnexpectedValueException If provided data is invalid
     *
     * @return mixed The specified user data.
     */
    public function getUserDataFromUsername($data, $username)
    {
        return $this->getDriver()->user->get($username, ['by' => 'login'])->{$data};
    }
}
