<?php namespace Assign3;

include "conn.php";
include "SQLUser.php";
include "SQLCustomer.php";

/**
 * MySQL implementation of the DB interface.
 */
class SQLDB implements DB
{
    private $userDb;
    private $customerDB;

    /**
     * Gets a MySQL implementation of the UserDB interface.
     */
    public function GetUserDB()
    {
        if (!isset($userDb)) {
            $userDb = new SQLUser();
        }
        return $userDb;
    }

    /**
     * Gets a MySQL implementation of the CustomerDB interface.
     */
    public function GetCustomerDB()
    {
        if (!isset($customerDB)) {
            $customerDB = new SQLCustomer();
        }
        return $customerDB;
    }
}