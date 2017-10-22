<?php
namespace PaulGibbs\WordpressBehatExtension\Context\Traits;

use Behat\Mink\Exception\DriverException;
use Behat\Mink\Exception\ExpectationException;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use UnexpectedValueException;

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
     * Create a user. If the user already exists, the existing user will be
     * compared with the user which was asked to be created. If all matches
     * no fault with be thrown. If it does not match then UnexpectedValueException
     * will be thrown.
     *
     * @param string $userLogin  User login name.
     * @param string $userEmail  User email address.
     * @param array  $args        Optional. Extra parameters to pass to WordPress.
     *
     * @throws \UnexpectedValueException
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

        try {
            $user = $this->getDriver()->user->create($args);
        } catch (UnexpectedValueException $exception) {
            $user = $this->getExistingMatchingUser($args);
        }

        $return_array = array(
            'id'   => $user->ID,
            'slug' => $user->user_nicename
        );

        return $return_array;
    }

    /**
     * Returns a user if all the passed parameters match
     * throws an UnexpectedValueException if not
     *
     * if there is return a user ID
     *
     * @throws \UnexpectedValueException
     * @return \WP_User $user
     */
    private function getExistingMatchingUser($args)
    {

        $user_id = $this->getUserIdFromLogin($args['user_login']);
        $user = $this->getDriver()->user->get($user_id);

        /* users can have more than one role, so treat this as a
         * special case. If role is specified then check its in the roles
         * array in the existing user. If an entire roles array is specified
         * then it will need to match exactly. The latter case is handled like any other
         * parameter.
         */

        // $user->role can either be a string with 1 role in it or an array of roles.
        if (array_key_exists('role', $args)) {
            if (is_string($user->roles)) {
                if ($user->roles != $args['role']) { // if its a string check it matches the role
                    throw new \UnexpectedValueException('User with login : ' . $user->user_login .
                        'exists, but role : ' . $args['role'] . ' does not match the applied role : ' . $user->roles);
                }
            } elseif (!in_array($args['role'], $user->roles)) { // if its an array check the role is in that array
                throw new \UnexpectedValueException('User with login : ' . $user->user_login .
                    'exists, but role : ' . $args['role'] . ' is not in the list of applied roles : ' . $user->roles);
            }
        }

        /* Loop through each of the passed arguements.
         * if they are arguments which apply to users
         * then check that that which exist matches that which was specified.
         */
        foreach ($args as $parameter => $value) {
            if ($parameter == 'password') {
                try {
                    if (!$this->getDriver()->user->validateCredentials($args['user_login'], $value)) {
                        throw new \UnexpectedValueException('User with login : ' . $user->user_login . ' exists but password is incorrect');
                    }
                } catch (UnsupportedDriverActionException $exception) {
                    // WPCLI can't do this yet.
                }
            }
            if ($this->isValidUserParameter($parameter) && $user->$parameter != $args[$parameter]) {
                throw new \UnexpectedValueException('User with login : ' . $user->user_login .
                    'exists, but ' . $parameter . ' is ' . $user->$parameter .
                    ' not ' . $args[$parameter] . 'which was specified');
            }
        }

        return $user;
    }

    /**
     * Checks to see if the passed in parameter applies to a user or not.
     *
     * @param string $user_parameter the parameter to be checked.
     *
     * @return boolean $retval True if the parameter does apply to a user.
     */
    private function isValidUserParameter(string $user_parameter)
    {
        $validUserParameters = array('id',
            'user_login',
            'display_name',
            'user_email',
            'user_registered',
            'roles',
//            'user_pass', - exclude the password for the moment - need special logic for it
            'user_nicename',
            'user_url',
            'user_activation_key',
            'user_status',
            'url');
        return in_array(strtolower($user_parameter), $validUserParameters);
    }

    /**
     * Get a User's ID from their username.
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
}
