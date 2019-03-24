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
        $sql .= "js.actual_start, js.actual_end, js.is_complete+0 AS complete ";
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
            $schedule->complete = $row['complete'];
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
        $sql .= "AND js.scheduled_end > '";
        $sql .= date("Y-m-d H:i:s", strtotime($start))."' ";
        $sql .= "ORDER BY js.scheduled_start;";
        $result = mysqli_query($conn, $sql);

        $schedules = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $schedule = new Schedule(
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

    
    /**
     * Get all active schedules for a given process.
     */
    public function getActiveSchedulesForProcess($process)
    {
        if (!isset($process)) {
            return null;
        }
        $conn = Conn::getDbConnection();
        $sql = "SELECT js.job_no, js.sequence, js.scheduled_start, js.scheduled_end, ";
        $sql .= "js.actual_start, js.actual_end, j.title, j.deadline, c.code, c.name, ";
        $sql .= "(SELECT MAX(a.sequence) FROM job_schedule a ";
        $sql .= "WHERE a.job_no = js.job_no AND is_complete = (1)) AS LastFinishedSequence ";
        $sql .= "FROM job_schedule js ";
        $sql .= "INNER JOIN job j ON j.job_no = js.job_no ";
        $sql .= "INNER JOIN customer c ON j.customer_code = c.code ";
        $sql .= "WHERE js.is_complete = (0) ";
        $sql .= "AND js.process_id = ".$process->id." ";
        $sql .= "ORDER BY js.scheduled_start;";
        $result = mysqli_query($conn, $sql);

        $schedules = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $schedule = new Schedule(
                $row['job_no'],
                $row['sequence'],
                $process,
                $row['scheduled_start'],
                $row['scheduled_end']
            );
            $schedule->actualStart = $row['actual_start'];
            $schedule->actualEnd = $row['actual_end'];
            $schedule->complete = 0;
            $schedule->lastFinishedSequence = $row['LastFinishedSequence'];
            $customer = new Customer($row['code'], $row['name'], null);
            $schedule->job = new Job($row['job_no'], $customer, $row['title'], $row['deadline'], 0);
            array_push($schedules, $schedule);
        }

        return $schedules;
    }

    /**
     * Gets the next available schedule for a given process
     */
    public function getNextSchedule($process, $start)
    {
        if (!isset($process)) {
            return null;
        }
        if (!isset($start)) {
            $start = date("Y-m-d H:i:s");
        }
        $sqlStart = "SELECT MAX(js.scheduled_end) AS next_start ";
        $sqlStart .= "FROM job_schedule js ";
        $sqlStart .= "INNER JOIN availability a ON js.process_id = a.process_id ";
        $sqlStart .= "WHERE js.scheduled_end > '";
        $sqlStart .= date("Y-m-d H:i:s", strtotime($start))."' ";

        $conn = Conn::getDbConnection();
        $result = mysqli_query($conn, $sqlStart);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);

        $times = array();
        if ($count == 1) {
            $newStart = $row['next_start'];

            if (!isset($newStart)) {
                $nextAvailability = $process->getNextAvailability($start);
                $currentDay = getDayNumber($start);
                if ($currentDay == $nextAvailability->dayOfWeek) {
                    $newStart = $start;
                } else {
                    // Find next occurance of availability date
                    $daysToAdd = $nextAvailability->dayOfWeek - $currentDay;
                    if ($daysToAdd < 0) {
                        $daysToAdd = 7 + $daysToAdd;
                    }
                    $timeFormatStart = date('H:i:s', strtotime($nextAvailability->startTime));
                    $newStart = date('Y-m-d '.$timeFormatStart, strtotime($start.' +'.$daysToAdd.' day'));
                }
            } else {
                $nextAvailability = $process->getNextAvailability($newStart);
            }
            $timeFormatEnd = date('H:i:s', strtotime($nextAvailability->endTime));
            $newEnd = date('Y-m-d '.$timeFormatEnd, strtotime($newStart));

            array_push($times, $newStart);
            array_push($times, $newEnd);
        }
        return $times;
    }

    /**
     * Books in a schedule for a given job process.
     */
    public function setSchedule($job, $sequence, $process, $start, $end)
    {
        if (!isset($job) || !isset($process) || !isset($start) || !isset($end)) {
            return false;
        }
        $conn = Conn::getDbConnection();
        $sql = "INSERT INTO job_schedule (job_no, sequence, process_id, ";
        $sql .= "scheduled_start, scheduled_end, is_complete) values ";
        $sql .= "('$job->jobNo', $sequence, $process->id, '";
        $sql .= date("Y-m-d H:i:s", strtotime($start))."', '";
        $sql .= date("Y-m-d H:i:s", strtotime($end))."', ";
        $sql .= "(0));";
        mysqli_query($conn, $sql);

        return true;
    }

    /**
     * Start a scheduled job operation
     */
    public function startSchedule($job_no, $sequence_no)
    {
        if (!isset($job_no)) {
            return false;
        }

        $conn = Conn::getDbConnection();
        $sql = "UPDATE job_schedule SET actual_start = '";
        $sql .= date("Y-m-d H:i:s");
        $sql .= "' WHERE job_no = '".$job_no."' ";
        $sql .= "AND sequence = ".$sequence_no.";";
        mysqli_query($conn, $sql);

        return true;
    }

    /**
     * Finish a scheduled job operation
     */
    public function finishSchedule($job_no, $sequence_no) {
        if (!isset($job_no)) {
            return false;
        }

        $conn = Conn::getDbConnection();
        $sql = "UPDATE job_schedule SET actual_end = '";
        $sql .= date("Y-m-d H:i:s");
        $sql .= "', is_complete = (1) ";
        $sql .= "WHERE job_no = '".$job_no."' ";
        $sql .= "AND sequence = ".$sequence_no.";";
        mysqli_query($conn, $sql);

        return true;
    }
}
