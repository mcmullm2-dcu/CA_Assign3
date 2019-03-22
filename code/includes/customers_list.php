<?php namespace Assign3;

/*
 * Writes out a list of all customers with an 'Edit' link beside each one.
 *
 * This file assumes the following variables are already defined:
 * - $edit_link: The URL of the current page, without the query string
 * - $customerDb: Database class providing customer methods.
 */

$customers = $customerDb->listCustomers(null);
echo '<table class="table table-striped">';
echo '<thead class="thead-dark"><tr>';
echo '<th scope="col">Code</th>';
echo '<th scope="col">Customer Name</th>';
echo '<th scope="col">Account Manager</th>';
echo '<th scope="col">Edit</th>';
echo '</tr></thead>';

echo '<tbody>';

foreach ($customers as $customer) {
    echo '<tr>';
    echo '<td>'.$customer->code.'</td>';
    echo '<td>'.$customer->name.'</td>';
    echo '<td>'.$customer->accountManager->name.'</td>';
    echo '<td><a href="'.$edit_link.'?mode=edit&id='.$customer->code.'">Edit</a></td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';
