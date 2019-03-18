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
        if (isset($_SESSION['user_id'])) {
            $id = $_SESSION['user_id'];
            $name = $_SESSION['user_name'];
            $email = $_SESSION['user_email'];
            $user = new User($id, $name, $email);
            $user->status = $_SESSION['user_status'];
            return $user;
        }
        return null;
    }

    /**
     * Converts this User instance into session data.
     */
    public function setSessionFromUser()
    {
        $_SESSION['user_id'] = $this->id;
        $_SESSION['user_name'] = $this->name;
        $_SESSION['user_email'] = $this->email;
        $_SESSION['user_status'] = $this->status;
    }
}
