<?php namespace Assign3;

include "conn.php";
include "SQLUser.php";

/**
 * MySQL implementation of the DB interface.
 */
class SQLDB implements DB
{
    private $userDb;

    /**
     * Gets a MySQL implementation of the UserDB interface.
     */
    public function getUserDB()
    {
        if (!isset($userDb)) {
            $userDb = new SQLUser();
        }
        return $userDb;
    }
}