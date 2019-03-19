<?php namespace Assign3;

include 'code/header.php'
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
        echo "\n<dl>";
        foreach ($user->dashboards as $dashboard) {
            echo "\n";
            echo '<dt><a href="'.$dashboard->url;
            echo '">'.$dashboard->name.'</a></dt>';
            echo '<dd>'.$dashboard->description.'</dd>';
        }
        echo "\n</dl>";
    } else {
        echo "<p>It appears you haven't been given access to any pages yet. ";
        echo "Please contact the administrator to get set up.</p>";
    }
}
?>

<?php include 'code/footer.php'
?>