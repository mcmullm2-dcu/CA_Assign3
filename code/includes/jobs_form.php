<?php namespace Assign3;

/*
 * Writes out the form that allows jobs to be added or edited.
 *
 * This file assumes the following variables are already defined:
 * - $mode: Indicates whether this is an 'add' or 'edit' form.
 * - $jobDb: Database class providing job methods.
 * - $customerDb: Database class providing customer methods.
 * - $edit_link: The URL of the current page, without the query string.
 */

echo '<h4>';
echo $mode == 'edit' ? 'Edit Job' : 'Add Job';
echo '</h4>';
if (isset($error)) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'.$error;
    echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
    echo '</div>';
}
if (isset($success)) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">'.$success;
    echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
    echo '</div>';
}
?>
<form method="post">
    <?php
    if ($mode == "add") {
    ?>
    <div class="form-group">
        <label for="job_no">Job Number:</label>
        <input type="text" id="job_no" name="job_no" class="form-control">
    </div>
    <?php
    } else {
        echo '<input type="hidden" name="job_no" value="'.$edit_job_no.'" />';
        $edit_job = $jobDb->getJob($edit_job_no);
    }
    ?>
    <div class="form-group">
        <label for="customer">Customer:</label>
        <select id="customer" name="customer" class="form-control">
            <?php
            $customers = $customerDb->listCustomerNames();
            foreach ($customers as $customer) {
                echo '<option value="'.$customer->code.'">'.$customer->name.'</option>';
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="title">Job Title:</label>
        <input type="text" id="title" name="title" class="form-control">
    </div>
    <div class="form-group">
        <label for="deadline">Deadline:</label>
        <input type="date" id="deadline" name="deadline" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    <?php
    if ($mode == "edit") {
        echo '<a href="'.$edit_link.'" class="btn btn-secondary">Cancel</a>';
    }
    ?>
</form>