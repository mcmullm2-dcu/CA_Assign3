<?php namespace Assign3;

/**
 * Interface to define functions relating to processes.
 */
interface ProcessDB
{
    public function getProcesses($role_name);
    public function getProcess($process_id);

    public function addProcess($process);
    public function updateProcess($process);

    public function addLabel($process, $label);
    public function addRole($process, $role);
    public function addAvailability($process, $availability);
    
    public function removeLabel($process, $label);
    public function removeRole($process, $role);
    public function removeAvailability($process, $availability);
}
