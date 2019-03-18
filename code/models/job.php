<?php namespace Assign3;

class Job
{
    public $jobNo;
    public $customer;
    public $title;
    public $deadline;
    public $isComplete;

    public function __construct($jobNo, $customer, $title, $deadline, $isComplete)
    {
        $this->jobNo = $jobNo;
        $this->customer = $customer;
        $this->title = $title;
        $this->deadline = $deadline;
        $this->isComplete = $isComplete;
    }
}
