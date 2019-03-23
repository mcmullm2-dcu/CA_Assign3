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

$jobDb = $db->GetJobDB();
$scheduleDb = $db->GetScheduleDB();

// URL without query string, from https://stackoverflow.com/a/6975045/5233918
$edit_link = strtok($_SERVER["REQUEST_URI"], '?');

if (isset($_GET['job'])) {
    $job_no = htmlspecialchars($_GET['job']);
    $job = $jobDb->getJob($job_no);
}

echo '<div class="row">';
if (isset($job)) {
    echo '<div class="col-sm-6">';
    include 'code/includes/schedule_jobdetails.php';
    echo '</div>';
    echo '<div class="col-sm-6">';
    include 'code/includes/schedule_pickworkflow.php';
    echo '</div>';
}
echo '</div>';
?>

<?php include 'code/footer.php'
?>