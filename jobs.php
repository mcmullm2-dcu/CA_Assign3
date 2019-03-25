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

$jobDb = $db->GetJobDB();
$customerDb = $db->GetCustomerDB();
$scheduleDb = $db->GetScheduleDB();

// URL without query string, from https://stackoverflow.com/a/6975045/5233918
$edit_link = strtok($_SERVER["REQUEST_URI"], '?');

$mode = 'add';
if (isset($_GET['mode'])) {
    $edit_job_no = htmlspecialchars($_GET['jobno']);
    $mode = htmlspecialchars($_GET['mode']);
}

// Handle postback
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_job_no = filter_var($_POST['job_no'], FILTER_SANITIZE_STRING);
    $post_customer_code = filter_var($_POST['customer'], FILTER_SANITIZE_STRING);
    $post_title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $post_deadline = filter_var($_POST['deadline'], FILTER_SANITIZE_STRING);

    if ($mode == 'add') {
        $test_job = $jobDb->getJob($post_job_no);
        if (isset($test_job)) {
            $error = "Sorry, that job number already exists. Please try another.";
            $old_job_no = $post_job_no;
            $old_customer_code = $post_customer_code;
            $old_title = $post_title;
            $old_deadline = $post_deadline;
        } else {
            $customer = $customerDb->getCustomer($post_customer_code);
            $new_job = new Job(
                $post_job_no,
                $customer,
                $post_title,
                $post_deadline,
                0
            );
            $jobDb->addJob($new_job);
            $success = "Successfully added job!";
        }
    } elseif ($mode == 'edit') {
        /*
        $manager = null;
        if ($post_user > 0) {
            $manager = new User($post_user, null, null);
        }
        $updated_customer = new Customer($post_code, $post_name, $manager);
        $customerDb->updateCustomer($updated_customer);
        header("location: ".$edit_link);
        */
    }
}

echo '<h2>'.$dashboard->name.'</h2>';
echo '<p>'.$dashboard->description.'</p>';

echo '<div class="row">';
echo '<div class="col-sm-9">';
include 'code/includes/jobs_list.php';
echo '</div>';

echo '<div class="col-sm-3">';
switch ($mode) {
    case 'add':
        include 'code/includes/jobs_form.php';
        break;
}
echo '</div>';
?>

</div>

<?php include 'code/footer.php'
?>