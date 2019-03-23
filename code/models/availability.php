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

    /**
     * Constructor that creates a new instance of a Availability object.
     */
    public function __construct($id, $process, $dayOfWeek, $startTime, $endTime)
    {
        $this->id = $id;
        $this->process = $process;
        $this->dayOfWeek = $dayOfWeek;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }

    /**
     * Gets a string representation of the day of the week, where 0 = Sunday, 1 = Monday, etc.
     * It uses the Modulo operator, so any value outside 0-6 will cycle around again.
     */
    public function getDayName()
    {
        $day = $this->dayOfWeek % 7;
        switch ($this->dayOfWeek) {
            case 0:
                return 'Sunday';
            case 1:
                return 'Monday';
            case 2:
                return 'Tuesday';
            case 3:
                return 'Wednesday';
            case 4:
                return 'Thursday';
            case 5:
                return 'Friday';
            default:
                return 'Saturday';
        }
    }

    /**
     * Gets a string representation of the available times for this instance.
     */
    public function getTimeRange()
    {
        $output = date("H:i", strtotime($this->startTime));
        $output .= ' - ';
        $output .= date("H:i", strtotime($this->endTime));
        return $output;
    }
}

