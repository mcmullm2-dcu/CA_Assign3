<?php namespace Assign3;

/*
 * Writes out all workflows with 'Details' link beside each one.
 *
 * This file assumes the following variables are already defined:
 * - $edit_link: The URL of the current page, without the query string
 * - $workflowDb: Database class providing process methods.
 */

$workflows = $workflowDb->getWorkflows();
echo '<table class="table">';
foreach ($workflows as $workflow) {
    echo '<tr';
    if (isset($edit_id) && $workflow->id == $edit_id) {
        echo ' class="table-active"';
    }
    echo '><td>'.$workflow->name.'</td>';
     echo '<td><a href="'.$edit_link.'?mode=details&id='.$workflow->id.'">Details</a></td>';
    echo '</tr>';
}
echo '</table>';
