<?php
namespace PaulGibbs\WordpressBehatExtension\Context\Awareness;

use Behat\Mink\Exception\DriverException;
use Behat\Mink\Exception\ExpectationException;
use Behat\Mink\Exception\UnsupportedDriverActionException;

trait UserAwareContextTrait
{

    /**
     * Log in the user.
     *
     * @param string $username
     * @param string $password
     * @param string $redirect_to Optional. After succesful log in, redirect browser to this path. Default = "/".
     *
     * @throws ExpectationException
     */
    public function logIn($username, $password, $redirect_to = '/')
    {
        if ($this->loggedIn()) {
            $this->logOut();
        }

        $this->visitPath('wp-login.php?redirect_to=' . urlencode($this->locatePath($redirect_to)));
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
     * @param string $user_login
     *            User login name.
     * @param string $user_email
     *            User email address.
     * @param array $args
     *            Optional. Extra parameters to pass to WordPress.
     *
     * @return array {
     *         @type int $id User ID.
     *         @type string $slug User slug (nicename).
     *         }
     */
    public function createUser($user_login, $user_email, $args = [])
    {
        $args['user_email'] = $user_email;
        $args['user_login'] = $user_login;

        $user = $this->getDriver()->user->create($args);

        return array(
            'id'   => $user->ID,
            'slug' => $user->user_nicename
        );
    }


    /**
     * Gets the id of a user from its login.
     *
     * @param string $user_login User login name.
     *
     * @return int $id User ID.
     */
    public function getUserIdFromLogin($login)
    {
        $this->getDriver()->getUserIdFromLogin($login);
    }


    /**
     * Delete a user.
     *
     * @param int   $id   ID of user to delete.
     * @param array $args Optional. Extra parameters to pass to WordPress.
     */
    public function deleteUser($id, $args = [])
    {
        $this->getDriver()->user->delete($id, $args);
    }
}
