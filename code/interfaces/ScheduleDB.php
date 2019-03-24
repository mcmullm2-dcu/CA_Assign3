<?php namespace Assign3;

/**
 * Interface to define functions relating to schedules.
 */
interface ScheduleDB
{
    public function getSchedules($job);
    public function getSchedulesForProcess($process, $start);
    public function getActiveSchedulesForProcess($process);
    public function getNextSchedule($process, $start);
    public function setSchedule($job, $sequence, $process, $start, $end);
    public function startSchedule($job_no, $sequence_no);
    public function finishSchedule($job_no, $sequence_no);
}
