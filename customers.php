<?php namespace Assign3;

include 'code/header.php';

if (!isset($user) || !$user->canAccessPage()) {
    // If user isn't allowed to access this page, redirect back to home.
    header("Location: index.php");
}
?>
    <h2>Welcome<?php
    if (isset($user)) {
        echo ' '.$user->name;
    }
?>!</h2>
<?php include 'code/footer.php'
?>