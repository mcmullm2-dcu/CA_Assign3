<?php namespace Assign3;

include_once("utilities.php");
include_once("interfaces/DB.php");
include_once("DAL/SQLDB.php");
include_once("models/models.php");

session_start();

$db = new SQLDB();
$user = User::getUserFromSession();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Job Schedules</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Cinzel" rel="stylesheet">
    <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
        integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS"
        crossorigin="anonymous">
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
<div class="container">
<h1>Job Schedules</h1>
<nav>
<?php include 'menu.php' ?>
</nav>
