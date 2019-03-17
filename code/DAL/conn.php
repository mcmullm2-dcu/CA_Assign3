<?php namespace Assign3;

function getDbConnection()
{
    $user='michael';
    $pass='dcu';
    $host='localhost';
    $db='assign3';
    $conn = mysqli_connect($host, $user, $pass, $db);

    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
    
    return $conn;
}
