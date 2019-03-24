<?php namespace Assign3;

/*
 * Writes out a drop-down list to choose processes for.
 *
 * This file assumes the following variables are already defined:
 * - $tasks: A list of scheduled jobs to list.
 * - $current_process: The process to filter scheduled jobs on.
 */

if (isset($tasks) && count($tasks) > 0) {
    echo '<table class="table">';
    echo '<thead class="thead-dark"><tr>';
    echo '<th scope="col">Job No</th>';
    echo '<th scope="col">Customer Name</th>';
    echo '<th scope="col">Title</th>';
    echo '<th scope="col">Scheduled Start</th>';
    echo '<th scope="col">Start/Stop</th>';
    echo '</tr></thead>';

    echo '<tbody>';

    foreach ($tasks as $task) {
        echo '<tr>';
        echo '<td>'.$task->jobNo.'</td>';
        echo '<td>'.$task->job->customer->name.'</td>';
        echo '<td>'.$task->job->title.'</td>';
        echo '<td>'.date('d/m/Y H:i', strtotime($task->start)).'</td>';
        echo '<td>';
        if (isset($task->actualStart)) {
            echo '<a href="'.$edit_link.'?jobno='.$task->jobNo;
            echo '&s='.$task->sequence;
            echo '&mode=finish" class="btn btn-danger">Finish</a>';
        } else {
            echo '<a href="'.$edit_link.'?jobno='.$task->jobNo;
            echo '&s='.$task->sequence;
            echo '&mode=start" class="btn btn-success">Start</a>';
        }
        echo '</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
} else {
    echo '<div class="alert alert-success">All caught up... no ';
    echo $current_process->name.' ';
    echo 'tasks to do!</div>';
}
