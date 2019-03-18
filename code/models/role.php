<?php namespace Assign3;

/**
 * Class to store details of roles available to different users.
 */
class Role
{
    public $id;
    public $name;

    /**
     * Constructor that creates a new instance of a Role object. 
     */
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}
