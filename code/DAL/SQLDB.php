<?php namespace Assign3;

include "conn.php";
include "SQLUser.php";

class SQLDB implements DB
{
    private $userDb;

    public function getUserDB()
    {
        if (!isset($userDb)) {
            $userDb = new SQLUser();
        }
        return $userDb;
    }
}