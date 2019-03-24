<?php namespace Assign3;
include 'code/header.php';
?>
    <h2>Welcome<?php
    if (isset($user)) {
        echo ' '.$user->name;
    }
?>!</h2>

<?php
if (!isset($user)) {
    echo '<p>To access this site, please <a href="login.php">log in</a>.</p>';
} else {
    if (isset($user->dashboards) && count($user->dashboards) > 0) {
        echo "<p>You have access to the following pages:</p>";
        echo '<div class="row">';
        foreach ($user->dashboards as $dashboard) {
            echo "\n";
            echo '<div class="col-xs-12 col-sm-6 col-md-4 mb-4">';
            echo '<div class="card h-100">';
            echo '<h5 class="card-header text-light bg-secondary">'.$dashboard->name.'</h5>';
            echo '<div class="card-body">';
            echo '<p class="card-text">'.$dashboard->description.'</p>';
            echo '</div>';
            echo '<div class="card-footer bg-white border-top-0 text-right">';
            echo '<a href="'.$dashboard->url.'" class="btn btn-secondary">Launch</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo "<p>It appears you haven't been given access to any pages yet. ";
        echo "Please contact the administrator to get set up.</p>";
    }
}
?>

<?php include 'code/footer.php'
?>