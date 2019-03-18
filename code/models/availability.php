<?php namespace Assign3;

class Availability
{
    public $id;
    public $process;
    public $dayOfWeek;
    public $startTime;
    public $endTime;
    public $streamCount;

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

