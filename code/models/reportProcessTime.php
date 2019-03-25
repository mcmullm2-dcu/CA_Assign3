<?php namespace Assign3;

/**
 * Class to store details about the time scheduled for a process
 */
class ReportProcessTime
{
    public $name;
    public $time;

    /**
     * Constructor that creates a new instance of a ReportProcessTime object.
     */
    public function __construct($name, $time)
    {
        $this->name = $name;
        $this->time = $time;
    }
}
