<?php namespace Assign3;

/*
 * Writes out all active jobs with links beside each one.
 *
 * This file assumes the following variables are already defined:
 * - $edit_link: The URL of the current page, without the query string
 * - $edit_code: The job number in the query string
 * - $jobDb: Database class providing job methods.
 * - $scheduleDb: Database class providing job methods.
 */

$jobs = $jobDb->getActiveJobs();
echo '<table class="table">';
echo '<thead class="table-dark">';
echo '<th>Job No</th>';
echo '<th>Title</th>';
echo '<th>Customer</th>';
echo '<th>Deadline</th>';
echo '<th>Status</th>';
echo '<th></th>';
echo '</thead>';

// Main table body
echo '<tbody>';
foreach ($jobs as $job) {
    $scheduleDb->getSchedules($job);
    $deadline = strtotime($job->deadline);
    $timediff = time() - $deadline;

    echo '<tr';
    if (isset($edit_code) && $job->jobNo == $edit_code) {
        echo ' class="table-active"';
    } elseif ($job->dueToday()) {
        echo ' class="table-warning"';
    } elseif ($timediff > 0) {
        echo ' class="table-danger"';
    }
    echo '><td>'.$job->jobNo.'</td>';
    echo '<td>'.$job->title.'</td>';
    echo '<td>'.$job->customer->name.'</td>';

    echo '<td>'.date('d-m-Y', $deadline).'</td>';
    echo '<td>'.$job->getStatus().'</td>';

    echo '<td>';
    if (isset($editable)) {
        echo '<a href="jobs.php?mode=edit&jobno='.$job->jobNo.'" title="Edit Job" class="mr-2">';
        echo '<span class="oi oi-pencil"></span></a> ';
    }
    if ($user->canSchedule() && !$job->isScheduled()) {
        echo '<a href="schedule.php?job='.$job->jobNo.'" title="Schedule Job">';
        echo '<span class="oi oi-clock"></span></a>';
    }
    echo '</td>';
    echo '</tr>';
}
echo '</tbody></table>';
