<?php include_once('asset/header.php'); ?>
<?php include_once('asset/nav.php'); ?>
<link rel="stylesheet" href="page\style_sign.css">

<div class="container">
    <h3 class="my-3">Login Page</h3>

    <?php
        include_once("config/Database.php");
        include_once("class/UserLogin.php");
        include_once("class/Utils.php");

        $connectDB = new Database();
        $db = $connectDB->getConnection();

        $user = new UserLogin($db);
        $bs = new Bootstrap();

        if (isset($_POST['signin'])) {
            $user->setUsername($_POST['username']);
            $user->setPassword($_POST['password']);

            if ($user->emailNotExists()) {
                $bs->DisplayAlert("Please check your Username or Password.", "danger");
            } else {
                if ($user->verifyPassword()) {
                    # User logged in successfully
                } else {
                    $bs->DisplayAlert("Please check your Username or Password.", "danger");
                }
            }
        }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" class="form-control" aria-describedby="username">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" aria-describedby="password">
        </div>
        <a href="index.php" class="btn btn-secondary form-button">Go Back</a>
        <button type="submit" name="signin" class="btn btn-primary form-button">Sign In</button>
        <p class="mt-3">No account? <a href="signup.php">Create one!</a></p>
    </form>
</div>

<?php include_once('asset/footer.php'); ?>
