<?php namespace Assign3;

/*
 * Writes out an 'Add' form to add a new process.
 *
 * This file assumes the following variables are already defined:
 * - $edit_link: The URL of the current page, without the query string
 * - $processDb: Database class providing process methods.
 */
?>
<h4>Add Process</h4>
<form method="post">
<div class="form-group">
    <label for="process_name">Name</label>
    <input type="text" class="form-control" id="process_name" name="process_name" required <?php
    if (isset($old_name)) {
        echo 'value="'.$old_name.'"';
    }
    ?>>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" id="isActive" name="is_active" <?php
  if (isset($old_active)) {
      echo $old_active ? 'checked' : '';
  } else {
      echo 'checked';
  }
  ?>>
  <label class="form-check-label" for="isActive">
    Process is active
  </label>
</div>
<button type="submit" class="btn btn-primary">Submit</button>
</form>