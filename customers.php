<?php namespace Assign3;

include 'code/header.php';

if (!isset($user) || !$user->canAccessPage()) {
    // If user isn't allowed to access this page, redirect back to home.
    header("Location: index.php");
}

$dashboard = $user->getPageDashboard();
if (!isset($dashboard)) {
    // If user doesn't have page information, redirect back to home.
    header("Location: index.php");
}

echo '<h2>'.$dashboard->name.'</h2>';
echo '<p>'.$dashboard->description.'</p>';

echo '<div class="row">';

$customerDb = $db->GetCustomerDB();
$userDb = $db->GetUserDB();
$users = $userDb->getUsers(null);

// URL without query string, from https://stackoverflow.com/a/6975045/5233918
$edit_link = strtok($_SERVER["REQUEST_URI"], '?');

$mode = 'add';
if (isset($_GET['mode'])) {
    $edit_id = htmlspecialchars($_GET['id']);
    $mode = htmlspecialchars($_GET['mode']);
}

// Handle postback
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_code = filter_var($_POST['customer_code'], FILTER_SANITIZE_STRING);
    $post_name = filter_var($_POST['customer_name'], FILTER_SANITIZE_STRING);
    $post_user = filter_var($_POST['customer_account_manager'], FILTER_SANITIZE_STRING);

    if ($mode == 'add') {
        $test_customer = $customerDb->getCustomer($post_code);
        if (isset($test_customer)) {
            $error = "Sorry, that customer code already exists. Please try another.";
            $old_code = $post_code;
            $old_name = $post_name;
            $old_user = $post_user;
        } else {
            $manager = null;
            $manager_id = (int)$post_user;
            if ($manager_id > 0) {
                foreach ($users as $user) {
                    if ($user->id == $manager_id) {
                        $manager = $user;
                        break;
                    }
                }
            }
            $new_customer = new Customer($post_code, $post_name, $manager);
            $customerDb->insertCustomer($new_customer);
            $success = "Successfully added $post_name to the customer list!";
        }
    } elseif ($mode == 'edit') {
        $manager = null;
        if ($post_user > 0) {
            $manager = new User($post_user, null, null);
        }
        $updated_customer = new Customer($post_code, $post_name, $manager);
        $customerDb->updateCustomer($updated_customer);
        header("location: ".$edit_link);
    }
}

// Main list of customers
$customers = $customerDb->listCustomers();
echo '<div class="col-sm-9">';
echo '<table class="table table-striped">';
echo '<thead><tr>';
echo '<th scope="col">Code</th>';
echo '<th scope="col">Customer Name</th>';
echo '<th scope="col">Account Manager</th>';
echo '<th scope="col">Edit</th>';
echo '</tr></thead>';

echo '<tbody>';

foreach ($customers as $customer) {
    echo '<tr>';
    echo '<td>'.$customer->code.'</td>';
    echo '<td>'.$customer->name.'</td>';
    echo '<td>'.$customer->accountManager->name.'</td>';
    echo '<td><a href="'.$edit_link.'?mode=edit&id='.$customer->code.'">Edit</a></td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';
echo '</div>';

// Form for adding / editing customers
?>

<div class="col-sm-3">
    <?php
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
            <input type="text" class="form-control" id="customer_name" name="customer_name" <?php
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
</div>


<?php include 'code/footer.php'
?>