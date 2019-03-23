<?php namespace Assign3;

/*
 * Writes out a job's basic details.
 *
 * This file assumes the following variables are already defined:
 * - $job: A populated job object.
 */
if (!isset($job)) {
    echo "Sorry, can't find the requested job";
} else {
    $deadline = strtotime($job->deadline);

    echo '<h4>Job '.$job->jobNo.':</h4>';
    echo '<dl class="row">';
    echo '<dt class="col-sm-3">Customer</dt><dd class="col-sm-9">'.$job->customer->name.'</dd>';
    echo '<dt class="col-sm-3">Title</dt><dd class="col-sm-9">'.$job->title.'</dd>';
    echo '<dt class="col-sm-3">Deadline</dt><dd class="col-sm-9">'.date('d-m-Y', $deadline).'</dd>';
    echo '</dl>';
}
