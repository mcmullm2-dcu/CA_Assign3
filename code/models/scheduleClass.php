<?php namespace Assign3;

/**
 * Class to store a job's schedule.
 */
class Schedule
{
    public $jobNo;
    public $job;
    public $sequence;
    public $process;
    public $start;
    public $end;
    public $actualStart;
    public $actualEnd;
    public $complete;
    public $lastFinishedSequence;

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

    /**
     * Dictates whether or not this schedule is available to start. This is the
     * case when the previous task in the sequence hasn't been finished yet.
     */
    public function isAvailable()
    {
        if (!isset($this->lastFinishedSequence)) {
            return $this->sequence == 0;
        } else {
            return $this->sequence == ($this->lastFinishedSequence + 1);
        }
    }
}
