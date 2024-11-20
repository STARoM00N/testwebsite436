<?php
include_once('config/Database.php');
include_once('class/UserLogin.php');
include_once('class/Utils.php');

$connectDB = new Database();
$db = $connectDB->getConnection();

$user = new UserLogin($db);
$bs = new Bootstrap();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debugging data from form
    var_dump($_POST); 

    $user->setUsername($_POST['username']);
    $user->setPassword($_POST['password']);

    if ($user->emailNotExists()) {
        $bs->DisplayAlert("User not found. Please check your Username or Password.", "danger");
    } else {
        $verifyResult = $user->verifyPassword();
        if ($verifyResult === true) {
            echo "<script>alert('Login successful! Redirecting to the dashboard...');</script>";
            echo "<script>window.location.href = 'mail.php';</script>";
            exit;
        } else {
            $bs->DisplayAlert("Incorrect password. Please try again.", "danger");
        }
    }
}
?>

<div class="container">
    <h3 class="my-3">Login Page</h3>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <a href="index.php" class="btn btn-secondary form-button">Go Back</a>
        <button type="submit" class="btn btn-primary form-button">Sign In</button>
        <p class="mt-3">No account? <a href="signup.php">Create one!</a></p>
    </form>
</div>
