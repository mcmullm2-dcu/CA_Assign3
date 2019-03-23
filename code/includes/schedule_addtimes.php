<?php namespace Assign3;

/*
 * Allows a user to set the set the times for each process.
 *
 * This file assumes the following variables are already defined:
 * - $job: A populated job object.
 * - $edit_link: The URL of the current page, without the query string
 * - $workflowDb: Database class providing workflow methods.
 */

echo '<h4>Step 2: Update Times</h4>';
echo '<form method="POST">';
echo '<input type="hidden" id="schedule_step" name="schedule_step" value="TimesSet">';
echo '<button type="submit" class="btn btn-primary">Next</button> ';
echo '<a href="'.$edit_link.'?job='.$job->jobNo.'" class="btn btn-secondary">Back</a>';
echo '</form>';
