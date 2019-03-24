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

$processDb = $db->GetProcessDB();
$scheduleDb = $db->GetScheduleDB();

$processes = $processDb->getUserProcesses($user);
$current_process_id = $processes[0]->id;
$current_process = $processes[0];

function getCurrentProcess($availableProcesses, $current)
{
    foreach ($availableProcesses as $p) {
        if ($p->id == $current) {
            return $p;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_process_id = (int) filter_var($_POST['select_process'], FILTER_SANITIZE_STRING);
    $current_process = getCurrentProcess($processes, $current_process_id);
}
$tasks = $scheduleDb->getActiveSchedulesForProcess($current_process);
include 'code/includes/tasks_processdropdown.php';
include 'code/includes/tasks_listactive.php';
?>

<?php include 'code/footer.php'
?>
