<?php namespace Assign3;

include "conn.php";
include "SQLUser.php";
include "SQLCustomer.php";
include "SQLProcess.php";
include "SQLWorkflow.php";
include "SQLJob.php";

/**
 * MySQL implementation of the DB interface.
 */
class SQLDB implements DB
{
    private $userDb;
    private $customerDB;
    private $processDB;
    private $workflowDB;
    private $jobDB;

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

    /**
     * Gets a MySQL implementation of the ProcessDB interface.
     */
    public function GetProcessDB()
    {
        if (!isset($processDB)) {
            $processDB = new SQLProcess();
        }
        return $processDB;
    }

    /**
     * Gets a MySQL implementation of the WorkflowDB interface.
     */
    public function GetWorkflowDB()
    {
        if (!isset($workflowDB)) {
            $workflowDB = new SQLWorkflow();
        }
        return $workflowDB;
    }

    /**
     * Gets a MySQL implementation of the JobDB interface.
     */
    public function GetJobDB()
    {
        if (!isset($jobDB)) {
            $jobDB = new SQLJob();
        }
        return $jobDB;
    }
}