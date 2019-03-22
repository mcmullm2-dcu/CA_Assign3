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
echo '<div class="col-sm-9">';
include 'code/includes/customers_list.php';
echo '</div>';

// Form for adding / editing customers
?>

<div class="col-sm-3">
    <?php
    include 'code/includes/customers_form.php';
    ?>
</div>


<?php include 'code/footer.php'
?>