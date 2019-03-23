<?php namespace Assign3;

/**
 * Class to store a job's schedule.
 */
class Job
{
    public $jobNo;
    public $sequence;
    public $process;
    public $start;
    public $end;
    public $actualStart;
    public $actualEnd;
    public $complete;

    /**
     * Constructor that creates a new instance of a Job object.
     */
    public function __construct($jobNo, $sequence, $process, $start, $end)
    {
        $this->jobNo = $jobNo;
        $this->sequence = $sequence;
        $this->process = $process;
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * Start processing this scheduled task
     */
    public function start()
    {
        $actualStart = date('Y-m-d H:i:s');
    }

    /**
     * Finish processing this scheduled task
     */
    public function finish()
    {
        $actualEnd = date('Y-m-d H:i:s');
    }

    /**
     * Gets the time allocated to this task (in seconds)
     */
    public function getScheduledTime()
    {
        $startTimestamp = strtotime($start);
        $endTimestamp = strtotime($end);

        return $endTimestamp - $startTimestamp;
    }
}