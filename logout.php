<?php namespace Assign3;
session_start();

if(session_destroy()) {
    header("Location: index.php");
}