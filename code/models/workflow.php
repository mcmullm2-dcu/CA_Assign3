<?php namespace Assign3;

class Workflow
{
    public $id;
    public $name;
    public $description;
    public $processes;

    public function __construct($id, $name, $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }
}
