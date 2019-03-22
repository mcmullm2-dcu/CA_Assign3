<?php namespace Assign3;

/**
 * Interface to define functions relating to processes.
 */
interface JobDB
{
    public function getActiveJobs();
    public function finishJob($job);
}
