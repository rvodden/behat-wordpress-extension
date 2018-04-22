<?php
namespace PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces;

interface UserElementInterface
{
    /**
     * Create a user.
     *
     * @param array $args Data used to create an object.
     *
     * @return mixed The new item.
     */
    public function create($args);

    /**
     * Retrieve a user.
     *
     * @param int|string $id   Object ID.
     * @param array      $args Optional data used to fetch an object.
     *
     * @throws \UnexpectedValueException
     *
     * @return mixed The item.
     */
    public function get($id, $args = []);

    /**
     * Checks that the username and password are correct.
     *
     * @param string $username
     * @param string $password
     *
     * @return boolean True if the username and password are correct.
     */
    public function validateCredentials(string $username, string $password);

    /**
     * Delete a user.
     *
     * @param int|string $id   User ID.
     * @param array      $args Optional data used to delete an object.
     */
    public function delete($id, $args = []);
}