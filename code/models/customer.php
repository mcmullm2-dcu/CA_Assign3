<?php namespace Assign3;

/**
 * Class to store a customer's details.
 */
class Customer
{
    public $code;
    public $name;
    public $accountManager;
    public $activeJobs;
    public $activeJobCount;

    /**
     * Constructor that creates a new instance of a Customer object.
     */
    public function __construct($code, $name, $accountManager)
    {
        $this->code = strtoupper($code);
        $this->name = $name;
        $this->accountManager = $accountManager;
    }
}
