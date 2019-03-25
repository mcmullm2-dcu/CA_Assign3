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

    /**
     * Get the total time scheduled for this job (in seconds).
     */
    public function totalScheduled()
    {
        if (!$this->isScheduled()) {
            return 0;
        }
        $total = 0;
        foreach ($this->schedule as $step) {
            $total += $step->getScheduledTime();
        }
        return $total;
    }

    /**
     * Get the percentage of this job complete
     */
    public function percentageComplete() {
        if (!$this->isScheduled()) {
            return 0;
        }
        $complete = 0;
        foreach ($this->schedule as $step) {
            if ($step->complete == "1") {
                $complete += $step->getScheduledTime();
            }
        }
        if ($complete == 0) {
            return 0;
        }
        return 100 * ($complete / $this->totalScheduled());
    }
}
