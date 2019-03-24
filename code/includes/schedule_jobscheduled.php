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

echo '<ul>';
$sequence = 0;
foreach ($workflow->processes as $process) {
    if ($sequence > 6) break; // *******************************************TMP
    echo '<li>'.$process->name.': '.$process->workflowEstimateTime.'</li>';
    if (!isset($process->availability)) {
        $time = $process->workflowEstimateTime;
        $process = $processDb->getProcess($process->id);
        $process->workflowEstimateTime = $time;
    }
    $nextTime = date("Y-m-d H:i:s");
    while ($process->workflowEstimateTime > 0) {
        $times = $scheduleDb->getNextSchedule($process, $nextTime);
        if (count($times) > 0) {
            echo '<br>Start Time: '.date("Y-m-d H:i:s", strtotime($times[0]));
            echo '<br>End Time: '.date("Y-m-d H:i:s", strtotime($times[1]));
        }
        $availableStart = strtotime($times[0]);
        $availableEnd = strtotime($times[1]);
        $availableSeconds = $availableEnd - $availableStart;

        $start = date("Y-m-d H:i:s", $availableStart);
        $end = null;

        echo '<br>Available Seconds: '.$availableSeconds;
        echo '<br>Required Seconds: ' . $process->workflowEstimateTime;
        if ($availableSeconds > $process->workflowEstimateTime) {
            // Set start and end time
            echo '<br>We have enough time!';
            echo '<br>Start Schedule: '.date("Y-m-d H:i:s", $availableStart);
            $endTime = $availableStart+$process->workflowEstimateTime;
            echo '<br>End Schedule: '.date("Y-m-d H:i:s", $endTime);
            $end = date("Y-m-d H:i:s", $endTime);
        } else {
            echo '<br>Not enough time, so let us split the process.';
            echo '<br>Start Schedule: '.date("Y-m-d H:i:s", $availableStart);
            echo '<br>End Schedule: '.date("Y-m-d H:i:s", $availableEnd).'<hr>';
            $end = date("Y-m-d H:i:s", $availableEnd);
        }
        $scheduleDb->setSchedule($job, $sequence++, $process, $start, $end);
        $process->workflowEstimateTime -= $availableSeconds;
        $nextTime = $end;
    }
}
echo '</ul>';

/*
// Roughwork:
// 1. Get day of week
echo getDayNumber("2019-03-24");
// 2. Download all availabilities for process
echo '<ul>';
$p = $processDb->getProcess($workflow->processes[0]->id);
$p->workflowEstimateTime = $workflow->processes[0]->workflowEstimateTime;

echo 'Process: '.$p->name;
foreach ($p->availability as $a) {
    echo '<li>'.$a->getDayName().': '.date("H:i", strtotime($a->startTime)).'</li>';
}
echo '</ul>';
// 3. Get all incomplete jobs for process after given time (null = from now)
//    Possibly not necessary. Just need to get the next available space.
$schedules = $scheduleDb->getSchedulesForProcess($p, null);
echo "Schedule Count: ".count($schedules);
// 4. Find next available space
$times = $scheduleDb->getNextSchedule($p, null);
if (count($times) > 0) {
    echo '<br>Start Time: '.date("Y-m-d H:i:s", strtotime($times[0]));
    echo '<br>End Time: '.date("Y-m-d H:i:s", strtotime($times[1]));
}
// 5. If available space > process time:
$availableStart = strtotime($times[0]);
$availableEnd = strtotime($times[1]);
$availableSeconds = $availableEnd - $availableStart;
echo '<br>Available Seconds: '.$availableSeconds;
echo '<br>Required Seconds: ' . $p->workflowEstimateTime;
if ($availableSeconds > $p->workflowEstimateTime) {
    //    * Set start and end time
    echo '<br>We have enough time!';
    echo '<br>Start Schedule: '.date("Y-m-d H:i:s", $availableStart);
    $endTime = $availableStart+$p->workflowEstimateTime;
    echo '<br>End Schedule: '.date("Y-m-d H:i:s", $endTime);
    $start = date("Y-m-d H:i:s", $availableStart);
    $end = date("Y-m-d H:i:s", $endTime);
    $scheduleDb->setSchedule($job, 0, $p, $start, $end);
} else {
    echo '<br>Not enough time, so let us split the process.';
    // 6. If available space < process time:
    //    * Fill available time
    //    * Reduce process time
    //    * Repeat from 4
}
*/
