<?php namespace Assign3;

interface UserDB
{
    public function loginUser($email, $password);
    public function getRoles($userId);
    public function getDashboards($userId);
}
