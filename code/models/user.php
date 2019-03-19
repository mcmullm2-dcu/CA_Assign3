<?php namespace Assign3;

/**
 * Class to store a user's details.
 */
class User
{
    public $id;
    public $name;
    public $email;
    public $status;
    public $roles;
    public $dashboards;

    /**
     * Constructor that creates a new instance of a User object.
     */
    public function __construct($id, $name, $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * Creates a new User instance from session data, if available.
     */
    public static function getUserFromSession()
    {
        if (isset($_SESSION['userData'])) {
            $user = $_SESSION['userData'];
            return $user;
        }
        return null;
    }

    /**
     * Converts this User instance into session data.
     */
    public function setSessionFromUser()
    {
        $_SESSION['userData'] = $this;
    }
}
