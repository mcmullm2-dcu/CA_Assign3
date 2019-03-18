<?php namespace Assign3;

class Customer
{
    public $code;
    public $name;
    public $accountManager;

    public function __construct($code, $name, $accountManager)
    {
        $this->code = $code;
        $this->name = $name;
        $this->accountManager = $accountManager;
    }
}
