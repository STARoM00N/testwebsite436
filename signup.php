<?php include_once('asset/header.php'); ?>
<?php include_once('asset/nav.php'); ?>

<link rel="stylesheet" href="page/style_sign.css">

<script>
function validateSignupForm() {
    const email = document.forms["signupForm"]["email"].value;
    const username = document.forms["signupForm"]["username"].value;
    const password = document.forms["signupForm"]["password"].value;
    const confirmPassword = document.forms["signupForm"]["confirm_password"].value;
    const firstName = document.forms["signupForm"]["first_name"].value;
    const lastName = document.forms["signupForm"]["last_name"].value;

    if (!email || !username || !password || !confirmPassword || !firstName || !lastName) {
        alert("Please fill out all fields.");
        return false;
    }

    const usernameRegex = /^[A-Za-z0-9]+$/;
    if (!usernameRegex.test(username)) {
        alert("Username can only contain English letters and numbers.");
        return false;
    }

    if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return false;
    }

    if (password.length < 6) {
        alert("Password must be at least 6 characters long.");
        return false;
    }

    return true;
}
</script>

<div class="service-panel" style="border-color:#2ecc71 !important;">
    <div class="container">
        <h3 class="my-3">Register Page</h3>

        <?php
            include_once("config/Database.php");
            include_once("class/UserRegister.php");
            include_once("class/Utils.php");

            $connectDB = new Database();
            $db = $connectDB->getConnection();

            $user = new UserRegister($db);
            $bs = new Bootstrap();

            if (isset($_POST['signup'])){
                $user->setUsername($_POST['username']);
                $user->setPassword($_POST['password']);
                $user->setConfirmPassword($_POST['confirm_password']);
                $user->setFName($_POST['first_name']);
                $user->setLName($_POST['last_name']);
                $user->setEmail($_POST['email']);

                if (!$user->checkEmail()) {
                    $bs->DisplayAlert("This email is already registered. Please try another.", "danger");
                } elseif (!$user->validatePassword()) {
                    $bs->DisplayAlert("Passwords do not match.", "danger");
                } elseif (!$user->checkPasswordLength()) {
                    $bs->DisplayAlert("Password must be at least 6 characters long.", "danger");
                } elseif ($user->createUser()) {
                    $bs->DisplayAlert("User created successfully.", "success");
                } else {
                    $bs->DisplayAlert("Failed to create user. Please check your input or try again later.", "danger");
                }
            }
        ?>

        <form name="signupForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST" onsubmit="return validateSignupForm()">
            <div class="mb-3">
                <label for="email address" class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" aria-describedby="email" placeholder="Enter your email">
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" class="form-control" aria-describedby="username" placeholder="Enter your username">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" aria-describedby="password" placeholder="Enter your password">
            </div>
            <div class="mb-3">
                <label for="confirm password" class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" aria-describedby="password" placeholder="Confirm your password">
            </div>
            <div class="mb-3">
                <label for="first name" class="form-label">First Name</label>
                <input type="text" name="first_name" class="form-control" aria-describedby="first name" placeholder="Enter your first name">
            </div>
            <div class="mb-3">
                <label for="last name" class="form-label">Last Name</label>
                <input type="text" name="last_name" class="form-control" aria-describedby="last name" placeholder="Enter your last name">
            </div>
            <a href="index.php" class="btn btn-secondary">Go Back</a>
            <button type="submit" name="signup" class="btn btn-primary">Sign Up</button>
        </form>
        <p class="mt-3">Already have an account? Let <a href="signin.php">Sign In!</a></p>
    </div>
</div>

<?php include_once('asset/footer.php'); ?>
