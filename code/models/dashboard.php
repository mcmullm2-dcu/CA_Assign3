<?php namespace Assign3;

/**
 * Class to store details about a dashboard, essentially a screen available to
 * a particular role.
 */
class Dashboard
{
    public $id;
    public $name;
    public $description;
    public $url;

    /**
     * Constructor that creates a new instance of a Dashboard object.
     */
    public function __construct($id, $name, $description, $url)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->url = $url;
    }
}
