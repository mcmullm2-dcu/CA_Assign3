<?php namespace Assign3;

/*
 * Writes out all process details.
 *
 * This file assumes the following variables are already defined:
 * - $edit_id: The ID of the process to edit.
 * - $processDb: Database class providing process methods.
 */
$process = $processDb->getProcess($edit_id);
if (!isset($process)) {
    echo "Sorry, can't find the requested process to edit";
} else {
    echo "<h4>Process Details:</h4>";
    echo '<h5>'.$process->name.' <small class="text-muted">(';
    echo $process->isActive ? "Active" : "Inactive";
    echo ")</small></h5>";

    // Availability
    if (isset($process->availability) && count($process->availability) > 0) {
        echo '<h6>Availability:</h6><div><dl class="dl-horizontal">';
        $currentDay = '';
        foreach ($process->availability as $avail) {
            if ($avail->getDayName() != $currentDay) {
                if (!empty($currentDay)) {
                    echo '</dd>';
                }
                echo '<dt>'.$avail->getDayName().'</dt><dd>';
                $currentDay = $avail->getDayName();
            } else {
                echo '<br>';
            }
            echo $avail->getTimeRange();
        }

        echo '</dd></dl></div><hr />';
    }

    // Roles
    if (isset($process->roles) && count($process->roles) > 0) {
        echo '<h6>Available to Roles:</h6><div>';
        foreach ($process->roles as $role) {
            echo '<button type="button" class="btn btn-secondary btn-sm">';
            echo $role->name;
            echo '</button> ';
        }
        echo '</div><hr />';
    }

    // Labels
    if (isset($process->labels) && count($process->labels) > 0) {
        echo '<h6>Labels:</h6><div>';
        foreach ($process->labels as $label) {
            echo '<button type="button" class="btn btn-secondary btn-sm">';
            echo $label->name;
            echo '</button> ';
        }
        echo '</div>';
    }
}
?>