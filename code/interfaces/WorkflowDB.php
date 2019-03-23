<?php namespace Assign3;

/**
 * Interface to define functions relating to workflows.
 */
interface WorkflowDB
{
    public function getWorkflows();
    public function getWorkflow($workflow_id);
    public function getProcesses($workflow);
}
