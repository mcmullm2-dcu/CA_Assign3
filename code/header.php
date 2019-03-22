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
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
    <link rel="stylesheet" href="bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap/icons/css/open-iconic-bootstrap.min.css">
    <script src="bootstrap/jquery-3.3.1.slim.min.js"></script>
    <script src="bootstrap/popper.min.js"></script>
    <script src="bootstrap/bootstrap.min.js"></script>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>

<div class="container">
<h1>Job Schedules</h1>
</div>
<nav class="grey container-fluid">
<div class="container">
<?php include 'menu.php' ?>
</div>
</nav>
<div class="container">
