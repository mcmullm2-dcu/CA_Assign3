<?php namespace Assign3;

/**
 * Class to store a job's details.
 */
class Job
{
    public $jobNo;
    public $customer;
    public $title;
    public $deadline;
    public $isComplete;

    /**
     * Constructor that creates a new instance of a Job object.
     */
    public function __construct($jobNo, $customer, $title, $deadline, $isComplete)
    {
        $this->jobNo = $jobNo;
        $this->customer = $customer;
        $this->title = $title;
        $this->deadline = $deadline;
        $this->isComplete = $isComplete;
    }
}
