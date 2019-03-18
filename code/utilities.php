<?php namespace Assign3;

/**
 * Write out a single menu link and mark it active if we're already on the
 * required page.
 *
 * @param string $url The URL of the menu link
 * @param string $text The text to display on the menu link
 */
function writeNavLink($url, $text)
{
    $current_page = basename($_SERVER['PHP_SELF']);
    echo "\t\n<li class=\"nav-item\">";
    echo "<a href=\"{$url}\" class=\"nav-link";
    if ($url == $current_page) {
        echo " active";
    }
    echo "\">{$text}</a></li>";
}
