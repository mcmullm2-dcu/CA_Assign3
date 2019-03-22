<?php namespace Assign3;

/**
 * Interface to define functions relating to processes.
 */
interface WorkflowDB
{
    public function getWorkflows();
    public function getProcesses($workflow);
}
