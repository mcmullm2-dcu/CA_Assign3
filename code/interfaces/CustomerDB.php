<?php namespace Assign3;

/**
 * Interface to define functions relating to customers.
 */
interface CustomerDB
{
    public function getCustomer($code);
    public function listCustomers();
    public function listAccountManagerCustomers($accountManager);
    public function insertCustomer($customer);
    public function updateCustomer($customer);
}
