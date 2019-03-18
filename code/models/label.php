<?php namespace Assign3;

/**
 * Class to store a label that can help describe a process.
 */
class Label
{
    public $id;
    public $name;

    /**
    * Constructor that creates a new instance of a Label object.
    */
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}
