<?php namespace Assign3;

/**
 * Class to store details about a process available to help produce a job.
 */
class Process
{
    public $id;
    public $name;
    public $isActive;
    public $labels;
    public $availability;
    public $roles;

    /**
     * Constructor that creates a new instance of a Process object.
     */
    public function __construct($id, $name, $isActive)
    {
        $this->id = $id;
        $this->name = $name;
        $this->isActive = (int)$isActive;
    }
}
