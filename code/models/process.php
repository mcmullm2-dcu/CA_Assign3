<?php namespace Assign3;

/**
 * Class to store details about a process available to help produce a job.
 */
class Process
{
    public $id;
    public $name;
    public $isActive;
    public $labels;
    public $availability;
    public $roles;
    public $workflowSequence;
    public $workflowEstimateTime;

    /**
     * Constructor that creates a new instance of a Process object.
     */
    public function __construct($id, $name, $isActive)
    {
        $this->id = $id;
        $this->name = $name;
        $this->isActive = (int)$isActive;
    }

    /**
     * Starting from today's date, find the next potential availability for this
     * process.
     */
    public function getNextAvailability($start)
    {
        if (!isset($this->availability) || count($this->availability) == 0) {
            return null;
        }
        if (!isset($start)) {
            $start = date("Y-m-d H:i:s");
        }
        $daynum = getDayNumber($start);
        $starttime = date("H:i:s", strtotime($start));
        while ($daynum < 7) {
            foreach ($this->availability as $a) {
                $aEndTime = date("H:i:s", strtotime($a->startTime));
                if ($a->dayOfWeek == $daynum && $starttime < $aEndTime) {
                    return $a;
                }
            }
            $daynum++;
            $starttime = date("H:i:s", mktime(0, 0, 0));
        }
        return $this->availability[0];
    }
}
