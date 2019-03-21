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

    /**
     * Indicates whether this job is due today.
     * Taken from: https://stackoverflow.com/a/25623057/5233918
     */
    public function DueToday() {
        $current = strtotime(date("Y-m-d"));
        $date    = strtotime($this->deadline);
        $datediff = $date - $current;
        $difference = floor($datediff/(60*60*24));
        return $difference == 0;
    }
}
