<?php namespace Assign3;

/**
 * Interface to define functions relating to users.
 */
interface UserDB
{
    public function getUsers($role_name);
    public function loginUser($email, $password);
    public function getRoles($user);
    public function getDashboards($user);
}
