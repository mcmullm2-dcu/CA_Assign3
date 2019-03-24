<?php namespace Assign3;

/*
 * Writes out a drop-down list to choose processes for.
 *
 * This file assumes the following variables are already defined:
 * - $processDb: Database class providing process methods.
 * - $user: The logged in user.
 */

if (count($processes) > 1) {
    echo '<form method="POST" class="form-inline mb-4" id="process_chooser">';
    echo '<label for="select_process" class="mr-1">Select a process: </label>';
    echo '<select id="select_process" name="select_process" class="form-control mr-1">';
    foreach ($processes as $process) {
        echo '<option value="'.$process->id.'"';
        if ($process->id == $current_process_id) {
            echo ' selected';
        }
        echo '>'.$process->name.'</option>';
    }
    echo '</select> ';
    echo '<button type="submit" class="btn btn-primary" id="process_chooser_submit">Get Live Tasks</button>';
    echo '</form>';
}
?>
<script>
$(document).ready(function() {
    $('#process_chooser_submit').hide();
    $("#select_process").change(function() {
        $('#process_chooser').submit();
    })
});
</script>