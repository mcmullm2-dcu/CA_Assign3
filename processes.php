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

$processDb = $db->GetProcessDB();
// URL without query string, from https://stackoverflow.com/a/6975045/5233918
$edit_link = strtok($_SERVER["REQUEST_URI"], '?');

$mode = 'add';
if (isset($_GET['mode'])) {
    $edit_id = htmlspecialchars($_GET['id']);
    $mode = htmlspecialchars($_GET['mode']);
}

echo '<div class="col-sm-6">';
include 'code/includes/processes_list.php';
echo '</div>';

echo '<div class="col-sm-6">';
switch ($mode) {
    case 'add':
        include 'code/includes/processes_add.php';
        break;
    case 'edit':
        include 'code/includes/processes_edit.php';
        break;
    case 'details':
        include 'code/includes/processes_details.php';
        break;
}

echo '</div>';
?>

</div>
<?php
include 'code/footer.php';