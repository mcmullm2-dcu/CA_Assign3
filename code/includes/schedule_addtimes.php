<?php namespace Assign3;

/*
 * Allows a user to set the set the times for each process.
 *
 * This file assumes the following variables are already defined:
 * - $job: A populated job object.
 * - $edit_link: The URL of the current page, without the query string
 * - $workflow: A populated Workflow instance.
 */

echo '<h4>Step 2: Update Times</h4>';
echo '<form method="POST">';
echo '<input type="hidden" id="schedule_step" name="schedule_step" value="TimesSet">';
echo '<input type="hidden" id="workflow_id" name="workflow_id" value="'.$workflow->id.'">';
echo '<strong>'.$workflow->name.'</strong>';
echo '<p>'.$workflow->description.'</p>';
echo '<p>Use the fields below to add the estimated time for each process, ';
echo 'or just accept the defaults.</p>';

// Processes
if (isset($workflow->processes) && count($workflow->processes) > 0) {
    echo '<table class="table">';
    echo '<thead class="table-dark"><tr><th>';
    echo 'Process:';
    echo '</th><th class="text-right">Est. Time (in minutes)</th></tr></thead><tbody>';
    $index = 0;
    foreach ($workflow->processes as $process) {
        echo '<tr><td>';
        echo $process->name;
        $time_minutes = round($process->workflowEstimateTime / 60);
        echo '<td class="text-right">';
        echo '<input type="number" class="time" name="process_'.$index.'" ';
        echo 'value="'.$time_minutes.'" min="1" step="1">';
        echo '<input type="hidden" name="process_' . $index . '_id" ';
        echo 'value="' . $process->id . '">';
        echo '</td></tr>';
        $index++;
    }
    echo '</tbody><tfoot><tr class="table-active"><td>Total Estimated Time:</td>';
    echo '<td class="text-right"><span id="estTotalTime"></span></td></table>';
}
echo '<button type="submit" class="btn btn-primary">Next</button> ';
echo '<a href="'.$edit_link.'?job='.$job->jobNo.'" class="btn btn-secondary">Cancel</a>';
echo '</form>';

?>
<script>
$(document).ready(function() {
    var total = $('#estTotalTime');
    getTotal(total);
    $(".time").change(function() {
        getTotal(total);
    })
});

// Get a running total of time, calculated on the client.
function getTotal(el) {
    var sum = 0;
    $('.time').each(function() {
        sum += Number($(this).val());
    });
    var hours = Math.floor(sum / 60);          
    var minutes = sum % 60;

    var output = minutes + " mins";
    if (hours > 1) {
        output = hours + " hours " + output;
    } else if (hours == 1) {
        output = hours + " hour " + output;
    }
    el.text(output);
}
</script>