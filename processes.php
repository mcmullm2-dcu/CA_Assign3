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

$processDb = $db->GetProcessDB();
// URL without query string, from https://stackoverflow.com/a/6975045/5233918
$edit_link = strtok($_SERVER["REQUEST_URI"], '?');

$mode = 'add';
if (isset($_GET['mode'])) {
    $edit_id = htmlspecialchars($_GET['id']);
    $mode = htmlspecialchars($_GET['mode']);
}

echo '<h2>'.$dashboard->name;
if ($mode != 'add') {
    echo ' <a href="'.$edit_link.'" class="btn btn-primary btn-circle" title="Add a new process"><span class="oi oi-plus"></span></a>';
}
echo '</h2>';
echo '<p>'.$dashboard->description.'</p>';

echo '<div class="row">';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_id = filter_var($_POST['process_id'], FILTER_SANITIZE_STRING);
    $post_name = filter_var($_POST['process_name'], FILTER_SANITIZE_STRING);
    $post_active = 0;
    if (isset($_POST['is_active'])) {
        $post_active = 1;
    }

    if ($mode == 'add') {
        // Attempt to add process
        $newProcess = new Process(null, $post_name, $post_active);
        if ($processDb->addProcess($newProcess)) {
            header("location: ".$edit_link);
        } else {
            $error = "Sorry, error trying to add this process.";
            $old_name = $post_name;
            $old_active = $post_active;
        }
    } else if ($mode == 'edit') {
        $oldProcess = $processDb->getProcess($edit_id);
        if (!isset($oldProcess)) {
            // TODO: No process to update
        } else {
            $oldProcess->name = $post_name;
            $oldProcess->isActive = $post_active;
            if ($processDb->updateProcess($oldProcess)) {
                header("location: ".$edit_link."?mode=details&id=".$edit_id);
            } else {
                $error = "Sorry, error trying to update this process.";
                $old_name = $post_name;
                $old_active = $post_active;
            }
        }
    }
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