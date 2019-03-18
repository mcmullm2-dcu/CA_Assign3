<?php namespace Assign3;

/**
 * Interface to define functions relating to users.
 */
interface UserDB
{
    public function loginUser($email, $password);
    public function getRoles($userId);
    public function getDashboards($userId);
}
