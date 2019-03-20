<?php namespace Assign3;

/**
 * MySQL implementation of CustomerDB methods.
 */
class SQLCustomer implements CustomerDB
{
    /**
     * Gets a single customer based on their customer code.
     */
    public function getCustomer($code) {
        $conn = Conn::getDbConnection();
        $sql = "SELECT c.code, c.name, c.account_manager, u.name AS userName, u.email ";
        $sql .= "FROM customer c LEFT JOIN user u ON c.account_manager = u.id ";
        $sql .= "WHERE c.code = '$code';";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);

        $customer = null;
        if ($count == 1) {
            $manager = new User($row['account_manager'], $row['userName'], $row['email']);
            $customer = new Customer($row['code'], $row['name'], $manager);
        }

        return $customer;
    }

    /**
     * List all customers in the database belonging to a particular account manager.
     * If accountManager is null, list all customers.
     */
    public function listCustomers($accountManager) {
        $conn = Conn::getDbConnection();
        $sql = "SELECT c.code, c.name, c.account_manager, u.name AS userName, u.email ";
        $sql .= "FROM customer c LEFT JOIN user u ON c.account_manager = u.id ";
        if (isset($accountManager) && $accountManager->id > 0) {
            $sql .= "WHERE u.Id = ".$accountManager->id." ";
        }
        $sql .= "ORDER BY c.name;";
        $result = mysqli_query($conn, $sql);

        $customers = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $manager = new User($row['account_manager'], $row['userName'], $row['email']);
            $customer = new Customer($row['code'], $row['name'], $manager);
            array_push($customers, $customer);
        }

        return $customers;
    }

    /**
     * Inserts a new customer into the database from a Customer instance.
     */
    public function insertCustomer($customer) {
        if (!isset($customer)) {
            return false;
        }
        $is_managed = isset($customer->accountManager) && ($customer->accountManager->id > 0);
        $sql_A = $is_managed ? ', account_manager' : '';
        $sql_B = $is_managed ? ', '.$customer->accountManager->id : '';

        $conn = Conn::getDbConnection();
        $sql = "INSERT INTO customer (code, name".$sql_A.") values ";
        $sql .= "('$customer->code', '$customer->name'".$sql_B.");";
        mysqli_query($conn, $sql);
        return true;
    }

    /**
     * Updates an existing customer with the supplied Customer instance.
     */
    public function updateCustomer($customer) {
        if (!isset($customer)) {
            return false;
        }
        $is_managed = isset($customer->accountManager) && ($customer->accountManager->id > 0);

        $conn = Conn::getDbConnection();
        $sql = "UPDATE customer SET name = '".$customer->name."', account_manager = ";
        $sql .= $is_managed ? $customer->accountManager->id : "null"; 
        $sql .= " WHERE code = '".$customer->code."';";
        mysqli_query($conn, $sql);
        
        return true;
    }
}
