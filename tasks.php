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
// URL without query string, from https://stackoverflow.com/a/6975045/5233918
$edit_link = strtok($_SERVER["REQUEST_URI"], '?');

$mode = '';
if (isset($_GET['mode'])) {
    $edit_jobno = htmlspecialchars($_GET['jobno']);
    $edit_seq = htmlspecialchars($_GET['s']);
    $mode = htmlspecialchars($_GET['mode']);
    if (isset($_GET['process'])) {
        $processQueryString = htmlspecialchars($_GET['process']);
    }

    switch ($mode) {
        case 'start':
            $scheduleDb->startSchedule($edit_jobno, $edit_seq);
            break;
        case 'finish':
            $scheduleDb->finishSchedule($edit_jobno, $edit_seq);
            break;
    }
}

$processes = $processDb->getUserProcesses($user);
if (isset($processQueryString)) {
    $current_process_id = $processQueryString;
    foreach ($processes as $process) {
        if ($process->id == $processQueryString) {
            $current_process = $process;
            break;
        }
    }
} else {
    $current_process_id = $processes[0]->id;
    $current_process = $processes[0];
}

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
