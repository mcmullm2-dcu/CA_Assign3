<?php namespace Assign3;

/**
 * Class to manage MySQL database connections
 */
class Conn
{
    public static $conn;

    /**
     * Get a MySQL database connection.
     */
    public static function getDbConnection()
    {
        if (!isset($conn))
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
        }
        
        return $conn;
    }
}
