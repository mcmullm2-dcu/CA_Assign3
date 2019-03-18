<?php namespace Assign3;

include "UserDB.php";

/**
 * Overall database interface to access all other database interfaces.
 */
interface DB
{
    public function getUserDB();
}