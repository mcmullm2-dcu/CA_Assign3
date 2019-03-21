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
//$userDb = $db->GetUserDB();
//$users = $userDb->getUsers(null);

// URL without query string, from https://stackoverflow.com/a/6975045/5233918
$edit_link = strtok($_SERVER["REQUEST_URI"], '?');

// Main list of customers
if (isset($user)) {
    $customers = $customerDb->listCustomers($user);
    echo '<div class="col-sm-12">';

    if (count($customers) > 0) {
        echo '<table class="table table-striped">';
        echo '<thead><tr>';
        echo '<th scope="col">Code</th>';
        echo '<th scope="col">Customer Name</th>';
        echo '<th scope="col">Account Manager</th>';
        echo '<th scope="col">Status</th>';
        echo '</tr></thead>';

        echo '<tbody>';

        foreach ($customers as $customer) {
            echo '<tr>';
            echo '<td>'.$customer->code.'</td>';
            echo '<td>'.$customer->name.'</td>';
            echo '<td>'.$customer->accountManager->name.'</td>';
            echo '<td>-</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
 
    } else {
        echo "<em>Sorry, you don't appear to be managing any customers</em>";
    }
    echo '</div>';
}

include 'code/footer.php';
?>