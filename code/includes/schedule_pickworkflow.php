<?php namespace Assign3;

/*
 * Allows a user to pick a workflow to apply to this job.
 *
 * This file assumes the following variables are already defined:
 * - $job: A populated job object.
 * - $workflowDb: Database class providing workflow methods.
 */

$workflows = $workflowDb->getWorkflows();
echo '<h4>Step 1: Select a workflow</h4>';
echo '<form method="POST">';
echo '<input type="hidden" id="schedule_step" name="schedule_step" value="WorkflowPicked">';
echo '<div class="form-group">';
echo '<label for="select_workflow">To schedule this job, select a workflow ';
echo 'from the following list, then click the "Next" button:</label>';
echo '<select class="form-control" id="select_workflow" name="select_workflow">';
foreach ($workflows as $workflow) {
    echo '<option value="'.$workflow->id.'">'.$workflow->name.'</option>';
}
echo '</select>';
echo '</div>';
echo '<button type="submit" class="btn btn-primary">Next</button>';
echo '</form>';
