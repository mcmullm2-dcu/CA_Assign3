<?php namespace Assign3;

class Dashboard
{
    public $id;
    public $name;
    public $description;
    public $url;

    public function __construct($id, $name, $description, $url)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->url = $url;
    }
}
