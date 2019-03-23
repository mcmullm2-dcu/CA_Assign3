<?php namespace Assign3;

/**
 * MySQL implementation of ScheduleDB methods.
 */
class SQLSchedule implements ScheduleDB
{
    /**
     * Gets all the schedules for a given job.
     */
    public function getSchedules($job)
    {
        if (!isset($job)) {
            return null;
        }

        $conn = Conn::getDbConnection();
        $sql = "SELECT js.sequence, js.process_id, p.name, p.active, ";
        $sql .= "js.scheduled_start, js.scheduled_end, ";
        $sql .= "js.actual_start, js.actual_end, js.is_complete ";
        $sql .= "FROM job_schedule js ";
        $sql .= "INNER JOIN process p ON js.process_id = p.id ";
        $sql .= "WHERE js.job_no = ".$job->jobNo." ";
        $sql .= "ORDER BY js.sequence ASC;";
        $result = mysqli_query($conn, $sql);

        $job->schedule = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $process = new Process($row['process_id'], $row['name'], $row['active']);
            $schedule = new Schedule(
                $job->jobNo,
                $row['sequence'],
                $process,
                $row['scheduled_start'],
                $row['scheduled_end']
            );
            $schedule->actualStart = $row['actual_start'];
            $schedule->actualEnd = $row['actual_end'];
            $schedule->process = $process;
            array_push($job->schedule, $schedule);
        }
    }

    /**
     * Get all active schedules for a given process after a given start date.
     */
    public function getSchedulesForProcess($process, $start)
    {
        if (!isset($process)) {
            return null;
        }
        if (!isset($start)) {
            $start = date("Y-m-d H:i:s");
        }
        $conn = Conn::getDbConnection();
        $sql = "SELECT js.job_no, js.sequence, js.scheduled_start, js.scheduled_end, ";
        $sql .= "js.actual_start, js.actual_end ";
        $sql .= "FROM job_schedule js ";
        $sql .= "WHERE js.is_complete = (0) ";
        $sql .= "AND js.process_id = ".$process->id." ";
        $sql .= "AND js.scheduled_end > '".date("Y-m-d H:i:s", strtotime($start))."' ";
        $sql .= "ORDER BY js.scheduled_start;";
        $result = mysqli_query($conn, $sql);

        $schedules = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $schedule = new Schedules(
                $row['job_no'],
                $row['sequence'],
                $process,
                $row['scheduled_start'],
                $row['scheduled_end']
            );
            $schedule->actualStart = $row['actual_start'];
            $schedule->actualEnd = $row['actual_end'];
            $schedule->complete = 0;
            array_push($schedules, $schedule);
        }

        return $schedules;
    }
}
