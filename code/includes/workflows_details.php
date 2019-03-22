<?php namespace Assign3;

/*
 * Writes out all workflow details.
 *
 * This file assumes the following variables are already defined:
 * - $edit_id: The ID of the process to show.
 * - $workflowDb: Database class providing workflow methods.
 */
$workflow = $workflowDb->getWorkflow($edit_id);
if (!isset($workflow)) {
    echo "Sorry, can't find the requested workflow";
} else {
    echo '<h4>Workflow Details:</h4>';
    echo '<h5>'.$workflow->name.'</h5>';
    echo '<p>'.$workflow->description.'</p>';

    // Processes
    if (isset($workflow->processes) && count($workflow->processes) > 0) {
        echo '<table class="table">';
        echo '<thead class="table-dark"><tr><th colspan="2">';
        echo 'Process Sequence:';
        echo '</th><th class="text-right">Est. Time (HH:mm)</th></tr></thead><tbody>';
        $total_time = 0;
        foreach ($workflow->processes as $process) {
            echo '<tr><td>';
            echo $process->workflowSequence.'</td><td>';
            echo $process->name;
            $total_time += $process->workflowEstimateTime;
            $timeStr = gmdate("H:i", $process->workflowEstimateTime);
            echo '<td class="text-right">'.$timeStr;
            echo '</td></tr>';
        }
        echo '</tbody><tfoot><tr class="table-active"><td colspan="2">Total Estimated Time:</td>';
        echo '<td class="text-right">';
        $timeStr = gmdate("H:i", $total_time);
        echo $timeStr.'</td>';
        echo '</tr></tfoot></table>';
    }
}
?>