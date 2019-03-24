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
    public $schedule;

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
    public function dueToday()
    {
        $current = strtotime(date("Y-m-d"));
        $date = strtotime($this->deadline);
        $datediff = $date - $current;
        $difference = floor($datediff/(60*60*24));
        return $difference == 0;
    }

    /**
     * Gets the current status of this job.
     */
    public function getStatus()
    {
        if ($this->isComplete) {
            return 'Finished';
        }

        if (!isset($this->schedule) || count($this->schedule) == 0) {
            return 'Not Scheduled';
        }

        foreach ($this->schedule as $step) {
            if ($step->complete == 0) {
                $status = $step->process->name;
                if (!isset($step->actualStart)) {
                    $status .= " (queued)";
                } else {
                    $status .= " (processing)";
                }
                return $status;
            }
        }
    }

    /**
     * Determine if this job is already scheduled.
     */
    public function isScheduled()
    {
        return (isset($this->schedule) && count($this->schedule) > 0);
    }
}
