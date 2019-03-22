<?php namespace Assign3;

/*
 * Writes out an edit form for editing a process.
 *
 * This file assumes the following variables are already defined:
 * - $edit_link: The URL of the current page, without the query string.
 * - $edit_id: The ID of the process to edit.
 * - $processDb: Database class providing process methods.
 */

$process = $processDb->getProcess($edit_id);
if (!isset($process)) {
    echo "Sorry, can't find the requested process to edit";
} else {
?>
<h4>Edit Process</h4>
<form method="post">
<input type="hidden" name="process_id" value="<?php echo $process->id; ?>">
<div class="form-group">
    <label for="process_name">Name</label>
    <input type="text" class="form-control" id="process_name" name="process_name" required <?php
    if (isset($old_name)) {
        echo 'value="'.$old_name.'"';
    } else {
        echo 'value="'.$process->name.'"';
    }
    ?>>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" id="isActive" name="is_active" <?php
  if (isset($old_active)) {
      echo $old_active == 1 ? 'checked' : '';
  } else {
      echo $process->isActive == 1 ? 'checked' : '';
  }
  ?>>
  <label class="form-check-label" for="isActive">
    Process is active
  </label>
</div>
<button type="submit" class="btn btn-primary">Submit</button>
<?php
}
echo '<a href="'.$edit_link.'" class="btn btn-secondary">Cancel</a>';
?>
</form>