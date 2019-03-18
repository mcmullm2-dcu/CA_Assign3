<?php namespace Assign3;

include 'code/header.php';
   
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userDb = $db::GetUserDB();
    $result = $userDb::loginUser($_POST['email'], $_POST['password']);
    if (isset($result) && empty($result->status)) {
        $result->setSessionFromUser();
        header("location: index.php");
    }
}
?>
    <h2>Login</h2>
    <?php
    if (isset($result->status)) {
        echo '<div class="alert alert-danger" role="alert">'.$result->status.'</div>';
    }
    ?>
    <form method="POST">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email"
                placeholder="Enter your email address"<?php
                if (isset($result->email))
                {
                    echo ' value="'.$result->email.'"';
                }
                ?> required>
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