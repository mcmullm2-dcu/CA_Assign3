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
            $this->populateAvailability($process);
            $this->populateLabels($process);
            $this->populateRoles($process);
        }

        return $process;
    }

    /**
     * Add a process to the database.
     */
    public function addProcess($process) {
        if (!isset($process)) {
            return false;
        }

        $conn = Conn::getDbConnection();
        $sql = "INSERT INTO process (name, active) values ";
        $sql .= "('$process->name', ($process->isActive));";
        mysqli_query($conn, $sql);

        // Get the newly added process ID.
        $process->id = mysqli_insert_id($conn);

        if ($process->id == 0) {
            return false;
        }

        $this->addProcessElements($process);

        return true;
    }

    /**
     * Update an existing process.
     */
    public function updateProcess($process) {
        if (!isset($process)) {
            return false;
        }

        $conn = Conn::getDbConnection();
        $sql = "UPDATE process SET name = '".$process->name."', active = ".$process.isActive;
        $sql .= " WHERE id = '".$process->id."';";
        mysqli_query($conn, $sql);
        
        // Clear out related data and reinsert from process instance.
        $this->removeAllLabels($process);
        $this->removeAllRoles($process);
        $this->removeAllAvailability($process);

        $this->addProcessElements($process);

        return true;
    }

    /**
     * Adds a label to an existing process.
     */
    public function addLabel($process, $label) {
        if (!isset($process) || !isset($label)) {
            return;
        }
        if ($label->id == 0) {
            // Label may not exist, so try adding it.
            $conn = Conn::getDbConnection();
            $sql_check = "SELECT id FROM label WHERE name = '".$label->name."';";
            $sql_insert = "INSERT INTO label (name) values ('".$label->name."');";
            $result = mysqli_query($conn, $sql_check);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $count = mysqli_num_rows($result);

            if ($count == 1) {
                // Label already exists, just need to add it to the process.
                $label->id = $row['id'];
            }
            else {
                mysqli_query($conn, $sql_insert);
                $label->id = mysqli_insert_id($conn);
            }

            if ($label->id > 0) {
                // Reference: https://dev.mysql.com/doc/refman/8.0/en/insert-on-duplicate.html
                $sql = "INSERT INTO process_label (process_id, label_id) ";
                $sql .= "values (".$process->id.", ".$label->id.") ";
                $sql .= "ON DUPLICATE KEY UPDATE label_id = label_id;";
                mysqli_query($conn, $sql);
            }
        }
    }

    /**
     * Adds a role to an existing process.
     */
    public function addRole($process, $role) {
        if (!isset($process) || !isset($role)) {
            return;
        }
        if ($role->id == 0) {
            // Role may not exist, so try adding it.
            $conn = Conn::getDbConnection();
            $sql_check = "SELECT id FROM role WHERE name = '".$role->name."';";
            $sql_insert = "INSERT INTO role (name) values ('".$role->name."');";
            $result = mysqli_query($conn, $sql_check);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $count = mysqli_num_rows($result);

            if ($count == 1) {
                // Role already exists, just need to add it to the process.
                $role->id = $row['id'];
            }
            else {
                mysqli_query($conn, $sql_insert);
                $role->id = mysqli_insert_id($conn);
            }

            if ($role->id > 0) {
                // Reference: https://dev.mysql.com/doc/refman/8.0/en/insert-on-duplicate.html
                $sql = "INSERT INTO process_role (process_id, role_id) ";
                $sql .= "values (".$process->id.", ".$role->id.") ";
                $sql .= "ON DUPLICATE KEY UPDATE role_id = role_id;";
                mysqli_query($conn, $sql);
            }
        }
    }

    /**
     * Adds availability details to an existing process.
     */
    public function addAvailability($process, $availability) {
        if (!isset($process) || !isset($availability)) {
            return;
        }
        if ($availability->id == 0) {
            // Availability does not exist yet, so try adding it.
            $conn = Conn::getDbConnection();
            $sql = "INSERT INTO availability (process_id, day_of_week, ";
            $sql .= "start_at, end_at, stream_count) values (";
            $sql .= $process->id.", ".$availability->dayOfWeek.", ";
            $sql .= $availability->startTime.", ".$availability->endTime.", ";
            $sql .= $availability->streamCount.");";
            mysqli_query($conn, $sql_insert);
            $availability->id = mysqli_insert_id($conn);
        }
    }

    /**
     * Removes a label from a process.
     */
    public function removeLabel($process, $label) {
        if (!isset($process) || !isset($label)) {
            return;
        }
        $conn = Conn::getDbConnection();
        $sql = "DELETE FROM process_label WHERE process_id = ".$process->id;
        $sql .= " AND label_id = ".$label->id.";";
        mysqli_query($conn, $sql);
        return;
    }

    /**
     * Removes a role from a process.
     */
    public function removeRole($process, $role) {
        if (!isset($process) || !isset($role)) {
            return;
        }
        $conn = Conn::getDbConnection();
        $sql = "DELETE FROM process_role WHERE process_id = ".$process->id;
        $sql .= " AND role_id = ".$role->id.";";
        mysqli_query($conn, $sql);
        return;
    }

    /**
     * Removes availability details from a process.
     */
    public function removeAvailability($process, $availability) {
        if (!isset($process) || !isset($availability)) {
            return;
        }
        $conn = Conn::getDbConnection();
        $sql = "DELETE FROM availability WHERE process_id = ".$process->id;
        $sql .= " AND id = ".$availability->id.";";
        mysqli_query($conn, $sql);
        return;
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
        $sql .= "FROM label l INNER JOIN process_label pl ON pl.label_id = l.id ";
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
                $row['id'],
                $process,
                $row['day_of_week'],
                $row['start_at'],
                $row['end_at'],
                $row['stream_count']
            );
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
        $sql .= "WHERE pr.process_id = ".$process->id." ";
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

    /**
     * Removes all labels from the database for a given process.
     */
    private function removeAllLabels($process) {
        if (!isset($process)) {
            return;
        }
        $conn = Conn::getDbConnection();
        $sql = "DELETE FROM process_label WHERE process_id = ".$process->id.";";
        mysqli_query($conn, $sql);
        return;
    }

    /**
     * Removes all roles from the database for a given process.
     */
    private function removeAllRoles($process) {
        if (!isset($process)) {
            return;
        }
        $conn = Conn::getDbConnection();
        $sql = "DELETE FROM process_role WHERE process_id = ".$process->id.";";
        mysqli_query($conn, $sql);
        return;
    }

    /**
     * Removes all availability details from the database for a given process.
     */
    private function removeAllAvailability($process) {
        if (!isset($process)) {
            return;
        }
        $conn = Conn::getDbConnection();
        $sql = "DELETE FROM availability WHERE process_id = ".$process->id.";";
        mysqli_query($conn, $sql);
        return;
    }

    /**
     * Adds all label, availability and role data related to a process.
     */
    private function addProcessElements($process) {
        if (!isset($process)) {
            return;
        }

        if (isset($process->labels) && count($process->labels) > 0) {
            foreach ($process->labels as $label) {
                $this->addLabel($process, $label);
            }
        }

        if (isset($process->roles) && count($process->roles) > 0) {
            foreach ($process->roles as $role) {
                $this->addRole($process, $role);
            }
        }

        if (isset($process->availability) && count($process->availability) > 0) {
            foreach ($process->availability as $availability) {
                $this->addAvailability($process, $availability);
            }
        }        
    }
}
