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

// URL without query string, from https://stackoverflow.com/a/6975045/5233918
$edit_link = strtok($_SERVER["REQUEST_URI"], '?');

// Main list of customers
if (isset($user)) {
    $customers = $customerDb->listCustomers($user);
    echo '<div class="col-sm-12">';

    if (count($customers) > 0) {
        echo '<table class="table table-sm">';
        echo '<thead class="thead-dark"><tr>';
        echo '<th scope="col">Code</th>';
        echo '<th colspan="2" scope="col">Customer Name</th>';
        echo '<th style="text-align:right" scope="col">Active Jobs</th>';
        echo '</tr></thead>';

        echo '<tbody>';

        foreach ($customers as $customer) {
            $customerDb->getActiveJobs($customer);

            echo '<tr class="table-active">';
            echo '<td class="py-3">'.$customer->code.'</td>';
            echo '<td colspan="2" class="py-3">'.$customer->name.'</td>';
            echo '<td style="text-align:right" class="py-3">'.$customer->activeJobCount.'</td>';
            echo '</tr>';

             if (!isset($customer->activeJobs) || count($customer->activeJobs) == 0) {
                echo '<tr class="text-muted small">';
                echo '<td colspan="4">';
                echo '<em>No jobs in progress</em>';
                echo '</td></tr>';
            } else {
                 foreach($customer->activeJobs as $job) {
                    $deadline = strtotime($job->deadline);
                    $timediff = time() - $deadline;

                    echo '<tr class="text-muted small';
                    if ($job->DueToday()) {
                        echo ' table-warning';
                    } elseif ($timediff > 0) {
                        echo ' table-danger';
                    } else {
                        echo ' table-light';
                    }
                    echo '"><td>';
                    echo $job->jobNo.'</td><td>'.$job->title.'</td><td>';
                    if ($job->DueToday()) {
                        echo 'DUE TODAY';
                    } else {
                        echo $timediff > 0 ? 'OVERDUE: ' : 'Due: ';
                        echo date('d-m-Y', $deadline);
                    } 
                    echo '</td><td>';
                    echo 'Status: Unknown';
                    echo '</td></tr>';
                }
            }
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