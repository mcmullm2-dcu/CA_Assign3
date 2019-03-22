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
    echo '<p>'.$workflow->description.'</p><hr />';

    // Processes
    if (isset($workflow->processes) && count($workflow->processes) > 0) {
        echo '<h6>Process Sequence:</h6>';
        echo '<ol>';
        foreach ($workflow->processes as $process) {
            echo '<li>';
            echo $process->name;
            echo '</li>';
        }
        echo '</ol>';
    }
}
?>