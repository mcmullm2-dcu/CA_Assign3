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
$workflowDb = $db->GetWorkflowDB();
$processDb = $db->GetProcessDB();

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
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['schedule_step']) && $_POST['schedule_step'] == "WorkflowPicked") {
            $workflow = $workflowDb->getWorkflow($_POST['select_workflow']);
            include 'code/includes/schedule_addtimes.php';
        } else if (isset($_POST['workflow_id'])) {
            $workflow = $workflowDb->getWorkflow($_POST['workflow_id']);
            include 'code/includes/schedule_jobscheduled.php';
            echo '<div class="row"><a href="'.$edit_link.'" class="btn btn-primary">Back to List</a></div>';
        }
    } else {
        include 'code/includes/schedule_pickworkflow.php';
    }
    echo '</div>';
} else {
    $unscheduled = true;
    include 'code/includes/jobs_list.php';
}
echo '</div>';
?>

<?php include 'code/footer.php'
?>