<?php namespace Assign3;

/**
 * Interface to define functions relating to processes.
 */
interface JobDB
{
    public function getActiveJobs();
    public function getUnscheduledJobs();
    public function getJob($jobNo);
    public function addJob($job);
    public function updateJob($job);
}
