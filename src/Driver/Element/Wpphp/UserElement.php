<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Driver\Element\Wpphp;

use PaulGibbs\WordpressBehatExtension\Driver\Element\BaseElement;
use UnexpectedValueException;

/**
 * WP-API driver element for managing user accounts.
 */
class UserElement extends BaseElement
{
    /**
     * Create an item for this element.
     *
     * @param array $args Data used to create an object.
     *
     * @throws \UnexpectedValueException
     *
     * @return \WP_User The new item.
     */
    public function create($args)
    {
        $args = wp_slash($args);
        $user = wp_insert_user($args);

        if (is_wp_error($user)) {
            throw new UnexpectedValueException(sprintf('[W615] Failed creating new user: %s', $user->get_error_message()));
        }

        return $this->get($user);
    }

    /**
     * Retrieve an item for this element.
     *
     * @param int|string $id Object ID.
     * @param array $args Optional data used to fetch an object.
     *
     * @throws \UnexpectedValueException
     *
     * @return \WP_User The item.
     */
    public function get($id, $args = [])
    {
        if (is_numeric($id) || ! isset($args['by'])) {
            $type = 'ID';
        } else {
            $type = $args['by'];
        }

        $user = get_user_by($type, $id);

        if (! $user) {
            throw new UnexpectedValueException(sprintf('[W616] Could not find user with ID %d', $id));
        }

        return $user;
    }

    /**
     * Checks that the username and password are correct.
     *
     * @param string $username
     * @param string $password
     *
     * @return boolean True if the username and password are correct.
     */
    public function validateCredentials(string $username, string $password)
    {
        $check = \wp_authenticate_username_password(null, $username, $password);
        return !\is_wp_error($check);
    }

    /**
     * Delete an item for this element.
     *
     * @param int $id Object ID.
     * @param array $args Optional data used to delete an object.
     *
     * @throws \UnexpectedValueException
     */
    public function delete($id, $args = [])
    {
        if (! function_exists('\wp_delete_user')) {
            require_once ABSPATH . 'wp-admin/includes/user.php';
        }

        $result = wp_delete_user($id, $args);

        if (! $result) {
            throw new UnexpectedValueException('[W617] Failed deleting user');
        }
    }
}
