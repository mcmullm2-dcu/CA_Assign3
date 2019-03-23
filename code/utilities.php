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
        echo " disabled";
    }
    echo "\">{$text}</a></li>";
}

/**
 * Get a number representing the day of week (0 - Sunday, 1 - Monday, etc) for a
 * given date. If no date is supplied, gets the current day instead.
 */
function getDayNumber($date)
{
    if (!isset($date)) {
        return date("N") % 7;
    } else {
        return date("N", strtotime($date)) % 7;
    }
}