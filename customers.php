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

// Main list of customers
$customerDb = $db->GetCustomerDB();
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
    <p>TODO</p>
</div>


<?php include 'code/footer.php'
?>