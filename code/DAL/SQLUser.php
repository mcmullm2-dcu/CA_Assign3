<?php namespace Assign3;

/**
 * MySQL implementation of UserDB methods.
 */
class SQLUser implements UserDB
{
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
        $conn = getDbConnection();
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

    public function getRoles($userId)
    {
    }

    public function getDashboards($userId)
    {
    }
}
