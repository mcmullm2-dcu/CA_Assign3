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
foreach ($workflow->processes as $process) {
    echo '<li>'.$process->name.': '.$process->workflowEstimateTime.'</li>';
}
echo '</ul>';

// Roughwork:
// 1. Get day of week
echo getDayNumber("2019-03-24");
// 2. Download all availabilities for process
echo '<ul>';
$p = $processDb->getProcess($workflow->processes[0]->id);
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
//    * Set start and end time
// 6. If available space < process time:
//    * Fill available time
//    * Reduce process time
//    * Repeat from 4