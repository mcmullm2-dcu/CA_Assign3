<?php namespace Assign3;

include "UserDB.php";
include "CustomerDB.php";
include "ProcessDB.php";
include "WorkflowDB.php";

/**
 * Overall database interface to access all other database interfaces.
 */
interface DB
{
    public function GetUserDB();
    public function GetCustomerDB();
    public function GetProcessDB();
    public function GetWorkflowDB();
}