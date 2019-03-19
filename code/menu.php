<?php namespace Assign3;

?>
<ul class="nav nav-pills">
<?php
writeNavLink("index.php", "Home");
if (isset($user)) {
    foreach ($user->dashboards as $dashboard) {
        writeNavLink($dashboard->url, $dashboard->name);
    }
}
if (isset($user) && !isset($user->status)) {
    writeNavLink("logout.php", "Logout");
} else {
    writeNavLink("login.php", "Login");
}
?>
</ul>

