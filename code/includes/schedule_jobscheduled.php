<?php namespace Assign3;

/*
 * Processes the scheduling for the job and confirms success.
 *
 * This file assumes the following variables are already defined:
 * - $job: A populated job object.
 * - $workflow: A populated Workflow instance.
 * - $workflowDb: Database class providing workflow methods.
 * - $processDb: Database class providing process methods.
 * - $scheduleDb: Database class providing scheduling methods.
 */

// Get all the processes and times entered by the user
$index = 0;
foreach ($workflow->processes as $process) {
    // Set the new estimated time in seconds.
    $process->workflowEstimateTime = $_POST['process_'.$index] * 60;
    $index++;
}

echo '<h4>Step 3: Scheduling</h4>';

echo '<p>Job <strong>'.$job->jobNo.'</strong> has been scheduled as follows:</p>';
echo '<ul>';
$sequence = 0;
foreach ($workflow->processes as $process) {
    echo '<li><strong>'.$process->name.'</strong>';
    if (!isset($process->availability)) {
        $time = $process->workflowEstimateTime;
        $process = $processDb->getProcess($process->id);
        $process->workflowEstimateTime = $time;
    }
    $nextTime = date("Y-m-d H:i:s");
    while ($process->workflowEstimateTime > 0) {
        $times = $scheduleDb->getNextSchedule($process, $nextTime);
        $availableStart = strtotime($times[0]);
        $availableEnd = strtotime($times[1]);
        $availableSeconds = $availableEnd - $availableStart;

        $start = date("Y-m-d H:i:s", $availableStart);
        $end = null;

        if ($availableSeconds > $process->workflowEstimateTime) {
            // Set start and end time
            $endTime = $availableStart+$process->workflowEstimateTime;
            $end = date("Y-m-d H:i:s", $endTime);
        } else {
            $end = date("Y-m-d H:i:s", $availableEnd);
        }
        $scheduleDb->setSchedule($job, $sequence++, $process, $start, $end);
        $process->workflowEstimateTime -= $availableSeconds;
        $nextTime = $end;

        echo '<br>'.date('d/m/Y', strtotime($start));
        echo ': '.date('H:i', strtotime($start));
        echo ' - '.date('H:i', strtotime($end));
    }
    echo "</li>";
}
echo '</ul>';

if (strtotime($end) < strtotime($job->deadline)) {
    echo '<div class="alert alert-success">';
    echo 'This job is successfully scheduled to be completed before its deadline.';
    echo '</div>';
} else {
    echo '<div class="alert alert-danger">';
    echo 'Warning: this job cannot be completed before its deadline of ';
    echo date('l, jS F, Y', strtotime($job->deadline));
    echo '</div>';
}
