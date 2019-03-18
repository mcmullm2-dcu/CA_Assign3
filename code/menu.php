<?php namespace Assign3;
    function writeNavLink($url, $text) {
        $current_page = basename($_SERVER['PHP_SELF']);
        echo "\t\n<li class=\"nav-item\">";
        echo "<a href=\"{$url}\" class=\"nav-link";
        if ($url == $current_page) {
            echo " active";
        }
        echo "\">{$text}</a></li>";
    }
?>
<ul class="nav nav-pills">
<?php
    writeNavLink("index.php", "Home");
    if (isset($_SESSION['login_name'])) {
        writeNavLink("logout.php", "Logout");
    } else {
        writeNavLink("login.php", "Login");
    }
?>
</ul>
