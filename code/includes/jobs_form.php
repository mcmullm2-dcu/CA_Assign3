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
        <input type="text" id="job_no" name="job_no" required <?php
        if (isset($old_job_no)) {
            echo 'class="form-control is-invalid" value="'.$old_job_no.'"';
        } else {
            echo 'class="form-control"';
        }
        ?>>
    </div>
    <?php
    } else {
        echo '<input type="hidden" id="job_no" name="job_no" value="'.$edit_job_no.'" />';
        echo '<h5>Job '.$edit_job_no.'</h5>';
        $edit_job = $jobDb->getJob($edit_job_no);
    }
    ?>
    <div class="form-group">
        <label for="customer">Customer:</label>
        <select id="customer" name="customer" class="form-control">
            <?php
            $customers = $customerDb->listCustomerNames();
            foreach ($customers as $customer) {
                echo '<option value="'.$customer->code.'"';
                if (
                    (isset($post_customer_code) && $customer->code == $post_customer_code)
                    || (isset($edit_job) && $customer->code == $edit_job->customer->code)
                ) {
                    echo ' selected';
                }
                echo '>'.$customer->name.'</option>';
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="title">Job Title:</label>
        <input type="text" id="title" name="title" required class="form-control"<?php
        if (isset($old_title)) {
            echo 'value="'.$old_title.'"';
        } elseif (isset($edit_title)) {
            echo 'value="'.$edit_title.'"';
        } elseif (isset($edit_job)) {
            echo 'value="'.$edit_job->title.'"';
        }
        ?>>
    </div>
    <div class="form-group">
        <label for="deadline">Deadline:</label>
        <input type="date" id="deadline" name="deadline" required class="form-control"<?php
         if (isset($old_deadline)) {
            echo 'value="'.$old_deadline.'"';
        } elseif (isset($post_deadline)) {
            echo 'value="'.$post_deadline.'"';
        } elseif (isset($edit_job)) {
            echo 'value="'.date("Y-m-d", strtotime($edit_job->deadline)).'"';
        }
        ?>>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    <?php
    if ($mode == "edit") {
        echo '<a href="'.$edit_link.'" class="btn btn-secondary">Cancel</a>';
    }
    ?>
</form>