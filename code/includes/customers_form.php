<?php namespace Assign3;

/*
 * Writes out the form that allows customers to be added or edited.
 *
 * This file assumes the following variables are already defined:
 * - $mode: Indicates whether this is an 'add' or 'edit' form.
 * - $customerDb: Database class providing customer methods.
 * - $users: An array of all users of this system.
 * - $edit_link: The URL of the current page, without the query string.
 *
 * Optionally, some variables may be available on postback to repopulate fields:
 * - $old_code: Previously entered customer code.
 * - $old_name: Previously entered customer name.
 * - $old_user: Previously entered customer account manager.
 * - $error: Feedback message when there is an error on the form.
 * - $success: Feedback message when a form is submitted successfully.
 */

echo '<h4>';
echo $mode == 'edit' ? 'Edit Customer' : 'Add Customer';
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
        <label for="customer_code">Code</label>
        <input type="text" id="customer_code" name="customer_code" <?php
        if (isset($old_code)) {
            echo 'class="form-control is-invalid" value="'.$old_code.'"';
        } else {
            echo 'class="form-control"';
        }
        ?>>
    </div>
    <?php
    } else {
        echo '<input type="hidden" name="customer_code" value="'.$edit_id.'" />';
        $edit_customer = $customerDb->getCustomer($edit_id);
    }
    ?>
    <div class="form-group">
        <label for="customer_name">Name</label>
        <input type="text" class="form-control" id="customer_name" name="customer_name" required <?php
        if (isset($old_name)) {
            echo 'value="'.$old_name.'"';
        } elseif (isset($edit_customer)) {
            echo 'value="'.$edit_customer->name.'"';
        }
        ?>>
    </div>
    <div class="form-group">
        <label for="customer_account_manager">Account Manager</label>
        <select class="form-control" id="customer_account_manager" name="customer_account_manager">
            <option value="0">(none)</option>
            <?php
            if (isset($users)) {
                foreach ($users as $user) {
                    echo '<option value="'.$user->id.'"';
                    if (isset($old_user) && $user->id == $old_user) {
                        echo ' selected';
                    } elseif ($mode == 'edit' && isset($edit_customer)) {
                        if (isset($edit_customer->accountManager) && $edit_customer->accountManager->id == $user->id) {
                            echo ' selected';
                        }
                    }
                    echo '>';
                    echo $user->name;
                    echo '</option>';
                }
            }
            ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    <?php
    if ($mode == "edit") {
        echo '<a href="'.$edit_link.'" class="btn btn-secondary">Cancel</a>';
    }
    ?>
</form>