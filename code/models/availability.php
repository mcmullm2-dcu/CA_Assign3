<?php namespace Assign3;

/**
 * Class to store the availability details of a process.
 */
class Availability
{
    public $id;
    public $process;
    public $dayOfWeek;
    public $startTime;
    public $endTime;
    public $streamCount;

    /**
     * Constructor that creates a new instance of a Availability object.
     */
    public function __construct($id, $process, $dayOfWeek, $endTime, $streamCount)
    {
        $this->id = $id;
        $this->process = $process;
        $this->dayOfWeek = $dayOfWeek;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->streamCount = $streamCount;
    }
}

