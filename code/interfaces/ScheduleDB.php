<?php namespace Assign3;

/**
 * Interface to define functions relating to schedules.
 */
interface ScheduleDB
{
    public function getSchedules($job);
}
