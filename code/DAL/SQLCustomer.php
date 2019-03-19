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
    $sql .= "WHERE c.code = $code;";
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
   * List all customers in the database.
   */
  public function listCustomers() {
    $conn = Conn::getDbConnection();
    $sql = "SELECT c.code, c.name, c.account_manager, u.name AS userName, u.email ";
    $sql .= "FROM customer c LEFT JOIN user u ON c.account_manager = u.id ";
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

  public function listAccountManagerCustomers($accountManager) {

  }

  public function insertCustomer($customer) {

  }

  public function updateCustomer($customer) {

  }
}
