<?php namespace Assign3;

/**
 * MySQL implementation of UserDB methods.
 */
class SQLUser implements UserDB
{
    /**
     * Gets a list of users belonging to a given role name. If role name is set
     * to null, then it returns all users.
     */
    public function getUsers($role_name)
    {
        $conn = Conn::getDbConnection();
        $sql = "SELECT u.id, u.email, u.name ";
        $sql .= "FROM user u ";
        if (isset($role_name)) {
            $sql .= "INNER JOIN role r on u.id = r.user_id ";
            $sql .= "WHERE r.name = '$role_name' ";
        }
        $sql .= "ORDER BY u.name;";
        $result = mysqli_query($conn, $sql);

        $users = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $user = new User($row['id'], $row['name'], $row['email']);
            array_push($users, $user);
        }

        return $users;
    }

    /**
     * Given the user's credentials, attempt to log a user into the system.
     *
     * @param string $email The user's email address.
     * @param string $password The user's password.
     *
     * @return User A populated User object if successful, or null.
     */
    public function loginUser($email, $password)
    {
        $error = '';
        $conn = Conn::getDbConnection();
        $email = mysqli_real_escape_string($conn, $email);
        $password = mysqli_real_escape_string($conn, $password);

        $sql = "SELECT id, email, password_hash, name FROM user WHERE email = '$email';";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);
        $user = null;

        if ($count == 1) {
            $hash = $row['password_hash'];
            $valid = password_verify($password, $hash);
            if (!$valid) {
                $error = "Your password is incorrect";
            } else {
                $id = $row['id'];
                $name = $row['name'];
                $user = new User($id, $name, $email);
            }
        } else {
            $error = "Your Login Name or Password is invalid";
        }

        if (!empty($error)) {
            $user = new User(0, '', $email);
            $user->status = $error;
        }

        return $user;
    }

    /**
     * Given a User, populate all the roles they belong to.
     */
    public function getRoles($user)
    {
        if (!isset($user)) {
            return;
        }

        $conn = Conn::getDbConnection();
        $sql = "SELECT r.id, r.name ";
        $sql .= "FROM user_role ur INNER JOIN role r ON ur.role_id = r.id ";
        $sql .= "WHERE ur.user_id = $user->id;";
        $result = mysqli_query($conn, $sql);

        $user->roles = array();
        while ($role = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $newRole = new Role($role['id'], $role['name']);
            array_push($user->roles, $newRole);
        }
    }

    /**
     * Given a User, populate all the dashboards they can access.
     */
    public function getDashboards($user)
    {
        if (!isset($user)) {
            return;
        }

        $conn = Conn::getDbConnection();
        $sql = "SELECT d.id, d.name, d.description, d.url ";
        $sql .= "FROM dashboard d INNER JOIN dashboard_roles dr ON dr.dashboard_id = d.id ";
        $sql .= "WHERE dr.role_id in (";
        $sql .= "SELECT u.role_id FROM user_role u WHERE u.user_id = $user->id);";
        $result = mysqli_query($conn, $sql);

        $user->dashboards = array();
        while ($dashboard = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $newDashboard = new Dashboard($dashboard['id'], $dashboard['name'], $dashboard['description'], $dashboard['url']);
            array_push($user->dashboards, $newDashboard);
        }
    }
}
