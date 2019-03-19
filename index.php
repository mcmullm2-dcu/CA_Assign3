<?php namespace Assign3;

include 'code/header.php'
?>
    <h2>Welcome<?php
    if (isset($user)) {
        echo ' '.$user->name;
        var_dump($user);
    }
?>!</h2>
<?php include 'code/footer.php'
?>