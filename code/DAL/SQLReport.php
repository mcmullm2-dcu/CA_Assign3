<?php namespace Assign3;

/**
 * MySQL implementation of ReportDB methods.
 */
class SQLReport implements ReportDB
{
    /**
     * Lists all upcoming scheduled process times so we can report on which
     * processes have the most work coming up.
     */
    public function listScheduledProcessTimes()
    {
        $conn = Conn::getDbConnection();
        $sql = "SELECT p.name, SUM(timediff(js.scheduled_end, js.scheduled_start)) AS ptime ";
        $sql .= "FROM process p ";
        $sql .= "LEFT JOIN job_schedule js ON js.process_id = p.id ";
        $sql .= "WHERE js.is_complete = (0) OR js.is_complete IS NULL ";
        $sql .= "GROUP BY p.name ";
        $sql .= "ORDER BY p.name;";
        $result = mysqli_query($conn, $sql);

        $processes = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $time = isset($row['ptime']) ? (int)$row['ptime'] : 0;
            $process = new ReportProcessTime($row['name'], $time);
            array_push($processes, $process);
        }
        return $processes;
    }
}
