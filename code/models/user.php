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

    /**
     * Determines whether this user can access the current page.
     */
    public function canAccessPage()
    {
        if (!isset($this->dashboards) || count($this->dashboards) == 0) {
            return false;
        }
        $current_page = basename($_SERVER['PHP_SELF']);
        foreach ($this->dashboards as $dashboard) {
            if ($dashboard->url == $current_page) {
                return true;
            }
        }

        return false;
    }

    /**
     * Gets the dashboard of the page currently being visited by this user
     */
    public function getPageDashboard()
    {
        $current_page = basename($_SERVER['PHP_SELF']);
        foreach ($this->dashboards as $dashboard) {
            if ($dashboard->url == $current_page) {
                return $dashboard;
            }
        }

        return null;
    }

    /**
     * Determines if this user is an administrator.
     */
    public function isAdmin()
    {
        if (isset($this->roles)) {
            foreach ($this->roles as $role) {
                if ($role->name == "Admin") {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Determines if this user is allowed to schedule jobs
     */
    public function canSchedule()
    {
        if (isset($this->dashboards)) {
            foreach ($this->dashboards as $dashboard) {
                if ($dashboard->url == 'schedule.php') {
                    return true;
                }
            }
        }

        return false;
    }
}
