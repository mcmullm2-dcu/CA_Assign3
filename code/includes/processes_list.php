<?php namespace Assign3;

/*
 * Writes out all processes with 'Edit' and 'Details' links beside each one.
 *
 * This file assumes the following variables are already defined:
 * - $edit_link: The URL of the current page, without the query string
 * - $processDb: Database class providing process methods.
 */

$processes = $processDb->getProcesses(null);
echo '<table class="table">';
foreach ($processes as $process) {
    echo '<tr>';
    echo '<td>'.$process->name.'</td>';
    echo '<td><a href="'.$edit_link.'?mode=edit&id='.$process->id.'">Edit</a></td>';
    echo '<td><a href="'.$edit_link.'?mode=details&id='.$process->id.'">Details</a></td>';
    echo '</tr>';
}
echo '</table>';
