<?php namespace Assign3;

/**
 * MySQL implementation of ProcessDB methods.
 */
class SQLProcess implements ProcessDB
{
    /**
     * Get all processes available to a given role name. Leave role name as null
     * to retrieve all processes.
     */
    public function getProcesses($role_name) {
        $conn = Conn::getDbConnection();
        $sql = "SELECT p.id, p.name, p.active ";
        $sql .= "FROM process p ";
        if (isset($role_name)) {
            $sql .= "INNER JOIN process_role pr ON pr.process_id = p.id ";
            $sql .= "INNER JOIN role r ON pr.role_id = r.id ";
            $sql .= "WHERE pr.role_id = r.id ";
        }
        $sql .= "ORDER BY p.name;";
        $result = mysqli_query($conn, $sql);

        $processes = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $process = new Process($row['id'], $row['name'], $row['active']);
            array_push($processes, $process);
        }

        return $processes;
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
