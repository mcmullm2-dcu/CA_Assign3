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
        if (!isset($process_id)) {
            return null;
        }
        $conn = Conn::getDbConnection();
        $sql = "SELECT id, name, active FROM process WHERE id = ".$process_id.";";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);

        $process = null;
        if ($count == 1) {
            $process = new Process($row['id'], $row['name'], $row['active']);
        }

        if (isset($process)) {
            populateAvailability($process);
            populateLabels($process);
            populateRoles($process);
        }

        return $process;
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

    /**
     * Populate the process labels array.
     */
    private function populateLabels($process) {
        if (!isset($process)) {
            return;
        }
        $conn = Conn::getDbConnection();
        $sql = "SELECT l.id, l.name ";
        $sql .= "FROM lable l INNER JOIN process_label pl ON pl.label_id = l.id ";
        $sql .= "WHERE pl.process_id = ".$process->id." ";
        $sql .= "ORDER BY l.name;";
        $result = mysqli_query($conn, $sql);

        $labels = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $label = new Label($row['id'], $row['name']);
            array_push($labels, $label);
        }

        $process->labels = $labels;

        return;
    }

    /**
     * Populate the process availability array.
     */
    private function populateAvailability($process) {
        if (!isset($process)) {
            return;
        }
        $conn = Conn::getDbConnection();
        $sql = "SELECT id, day_of_week, start_at, end_at, stream_count ";
        $sql .= "FROM availability ";
        $sql .= "WHERE process_id = ".$process->id." ";
        $sql .= "ORDER BY day_of_week, start_at;";
        $result = mysqli_query($conn, $sql);

        $availability = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $avail = new Availability(
                $row['id'], $process, $row['day_of_week'], $row['start_at'],
                $row['end_at'], $row['stream_count']);
            array_push($availability, $label);
        }

        $process->availability = $availability;

        return;
    }

    /**
     * Populate the process roles array.
     */
    private function populateRoles($process) {
        if (!isset($process)) {
            return;
        }
        $conn = Conn::getDbConnection();
        $sql = "SELECT r.id, r.name ";
        $sql .= "FROM role r INNER JOIN process_role pr ON pr.role_id = r.id ";
        $sql .= "WHERE rl.process_id = ".$process->id." ";
        $sql .= "ORDER BY r.name;";
        $result = mysqli_query($conn, $sql);

        $roles = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $role = new Role($row['id'], $row['name']);
            array_push($roles, $role);
        }

        $process->roles = $roles;

        return;
    }
}
