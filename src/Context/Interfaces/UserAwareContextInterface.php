<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Context\Interfaces;

use Behat\Mink\Exception\ExpectationException;
use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\UserElementInterface;

interface UserAwareContextInterface
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
    public function logIn(string $username, string $password, string $redirect_to = '/');

    /**
     * Log the current user out.
     */
    public function logOut();

    /**
     * Determine if the current user is logged in or not.
     *
     * @return bool
     */
    public function loggedIn(): bool;

    /**
     * Create a user.
     *
     * If the user already exists, the existing user will be
     * compared with the user which was asked to be created. If all matches
     * no fault with be thrown. If it does not match then UnexpectedValueException
     * will be thrown.
     *
     * @param string $user_login User login name.
     * @param string $user_email User email address.
     * @param array  $args       Optional. Extra parameters to pass to WordPress.
     *
     * @return array {
     *             @type int $id User ID.
     *             @type string $slug User slug (nicename).
     *         }
     */
    public function createUser(string $user_login, string $user_email, array $args = []): array;

    /**
     * Get a user's ID from their username.
     *
     * @param string $username The username of the user to get the ID of.
     *
     * @return int ID of the user.
     */
    public function getUserIdFromLogin(string $username): int;

    /**
     * Delete a user.
     *
     * @param int $user_id ID of user to delete.
     * @param array $args  Optional. Extra parameters to pass to WordPress.
     */
    public function deleteUser(int $user_id, array $args = []);

    /**
     * Get a piece of user data from their username.
     *
     * @param string $data     The user data to return (the name of a column from the WP_Users table).
     * @param string $username The username of the user to fetch a property from.
     *
     * @return mixed The specified user data.
     */
    public function getUserDataFromUsername(string $data, string $username);

    /**
     * Set the userElement
     *
     * @param UserElementInterface $userElement
     */
    public function setUserElement(UserElementInterface $userElement);

}