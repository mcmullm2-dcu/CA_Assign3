<?php namespace Assign3;

include 'code/header.php';
   
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = getDbConnection();
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); 
    
    $sql = "SELECT password_hash, name FROM user WHERE email = '$email';";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $count = mysqli_num_rows($result);
    
    if ($count == 1) {
        $hash = $row['password_hash'];
        $valid = password_verify($password, $hash);
        if (!$valid) {
            $error = "Your password is incorrect";
        } else {
            $_SESSION['login_email'] = $email;
            $_SESSION['login_name'] = $row['name'];;

            header("location: index.php");
        }
    } else {
        $error = "Your Login Name or Password is invalid";
    }
}
?>
    <h2>Login</h2>
    <?php
    if (isset($error)) {
        echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
    }
    ?>
    <form method="POST">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email"
                placeholder="Enter your email address"<?php if (isset($email)) { echo ' value="'.$email.'"'; } ?> required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password"
                placeholder="Enter your password" required>
        </div>
        <input type="submit" name="submitButton" class="btn btn-primary">
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
<?php include 'code/footer.php'
?>