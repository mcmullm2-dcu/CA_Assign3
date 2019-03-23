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
$scheduleDb = $db->GetScheduleDB();

// URL without query string, from https://stackoverflow.com/a/6975045/5233918
$edit_link = strtok($_SERVER["REQUEST_URI"], '?');

$mode = '';
if (isset($_GET['mode'])) {
    $edit_code = htmlspecialchars($_GET['code']);
    $mode = htmlspecialchars($_GET['mode']);
}

echo '<h2>'.$dashboard->name.'</h2>';
echo '<p>'.$dashboard->description.'</p>';

echo '<div class="row">';
echo '<div class="col-sm-9">';
include 'code/includes/jobs_list.php';
echo '</div>';

/* echo '<div class="col-sm-3">';
switch ($mode) {
    case 'details':
        echo 'Details';
        break;
}
echo '</div>';*/
?>

</div>

<?php include 'code/footer.php'
?>