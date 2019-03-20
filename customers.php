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

// Handle postback
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_code = $_POST['customer_code'];
    $post_name = $_POST['customer_name'];
    $post_user = $_POST['customer_account_manager'];
    $test_customer = $customerDb->getCustomer($post_code);
    if (isset($test_customer)) {
        $error = "Sorry, that customer code already exists. Please try another.";
        $old_code = $post_code;
        $old_name = $post_name;
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
}

// Main list of customers
$customers = $customerDb->listCustomers();
echo '<div class="col-sm-9">';
echo '<table class="table table-striped">';
echo '<thead><tr>';
echo '<th scope="col">Code</th>';
echo '<th scope="col">Customer Name</th>';
echo '<th scope="col">Account Manager</th>';
echo '</tr></thead>';

echo '<tbody>';
foreach ($customers as $customer) {
    echo '<tr>';
    echo '<td>'.$customer->code.'</td>';
    echo '<td>'.$customer->name.'</td>';
    echo '<td>'.$customer->accountManager->name.'</td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';
echo '</div>';

// Form for adding / editing customers
?>

<div class="col-sm-3">
    <h4>Add Customer</h4>
    <?php
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
        <div class="form-group">
            <label for="customer_name">Name</label>
            <input type="text" class="form-control" id="customer_name" name="customer_name" <?php
            if (isset($old_name)) {
                echo 'value="'.$old_name.'"';
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
                            echo '<option value="'.$user->id.'">';
                            echo $user->name;
                            echo '</option>';
                        }
                    }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>


<?php include 'code/footer.php'
?>