<?php namespace Assign3;

/**
 * MySQL implementation of JobDB methods.
 */
class SQLJob implements JobDB
{
    /**
     * Get an array of all active jobs.
     */
    public function getActiveJobs()
    {
        $conn = Conn::getDbConnection();
        $sql = "SELECT j.job_no, c.code, c.name AS CustomerName, u.id, u.email, ";
        $sql .= "u.name AS AccountManager, j.title, j.deadline ";
        $sql .= "FROM job j LEFT JOIN customer c ON j.customer_code = c.code ";
        $sql .= "LEFT JOIN user u ON c.account_manager = u.id ";
        $sql .= "WHERE j.complete = (0) ";
        $sql .= "ORDER BY j.deadline, j.job_no ASC;";
        $result = mysqli_query($conn, $sql);

        $jobs = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $customer = null;
            $manager = null;

            if (isset($row['AccountManager'])) {
                $manager = new User($row['id'], $row['AccountManager'], $row['email']);
            }
            if (isset($row['CustomerName'])) {
                $customer = new Customer($row['code'], $row['CustomerName'], $manager);
            }
            $job = new Job($row['job_no'], $customer, $row['title'], $row['deadline'], 0);
            array_push($jobs, $job);
        }

        return $jobs;
    }

    /**
     * Get a populated job object from a given job number.
     */
    public function getJob($jobNo)
    {
        $conn = Conn::getDbConnection();
        $sql = "SELECT j.job_no, c.code, c.name AS CustomerName, u.id, u.email, ";
        $sql .= "u.name AS AccountManager, j.title, j.deadline ";
        $sql .= "FROM job j LEFT JOIN customer c ON j.customer_code = c.code ";
        $sql .= "LEFT JOIN user u ON c.account_manager = u.id ";
        $sql .= "WHERE j.job_no = ".$jobNo. ";";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);

        $job = null;
        if ($count == 1) {
            $customer = null;
            $manager = null;

            if (isset($row['AccountManager'])) {
                $manager = new User($row['id'], $row['AccountManager'], $row['email']);
            }
            if (isset($row['CustomerName'])) {
                $customer = new Customer($row['code'], $row['CustomerName'], $manager);
            }
            $job = new Job($row['job_no'], $customer, $row['title'], $row['deadline'], 0);
        }

        return $job;
    }

    /**
     * Adds a job to the database.
     */
    public function addJob($job) {
        if (!isset($job)) {
            return false;
        }

        $conn = Conn::getDbConnection();
        $customer = $job->customer;
        $sql = "INSERT INTO job (job_no, customer_code, title, deadline, complete) values ";
        $sql .= "('$job->jobNo', '$customer->code', '$job->title', '";
        $sql .= date("Y-m-d H:i:s", strtotime($job->deadline))."', (0));";
        mysqli_query($conn, $sql);

        return true;       
    }
}
