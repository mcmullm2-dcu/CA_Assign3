<?php namespace Assign3;

/**
 * MySQL implementation of ProcessDB methods.
 */
class SQLProcess implements ProcessDB
{
    /**
     * Get all processes available to a given role name.
     */
    public function getProcesses($role_name) {
    }

    /**
     * Get a fully populated Process object based on its ID.
     */
    public function getProcess($process_id) {
    }

    /**
     * Add a process to the database.
     */
    public function addProcess($process) {
    }

    /**
     * Update an existing process.
     */
    public function updateProcess($process) {
    }

    /**
     * Adds a label to an existing process.
     */
    public function addLabel($process, $label) {
    }

    /**
     * Adds availability details to an existing process.
     */
    public function addAvailability($process, $availability) {
    }

    /**
     * Removes a label from a process.
     */
    public function removeLabel($process, $label) {
    }

    /**
     * Removes availability details from a process.
     */
    public function removeAvailability($process, $availability) {
    }
}
