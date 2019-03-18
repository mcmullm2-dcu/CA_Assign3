<?php namespace Assign3;

class Process
{
    public $id;
    public $name;
    public $isActive;

    public function __construct($id, $name, $isActive)
    {
        $this->id = $id;
        $this->name = $name;
        $this->isActive = $isActive;
    }
}
