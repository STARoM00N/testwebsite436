<?php
ob_start();
include_once('config/Database.php');
include_once('class/UserLogin.php');

// เชื่อมต่อฐานข้อมูล
$connectDB = new Database();
$db = $connectDB->getConnection();

// สร้าง Object UserLogin
$user = new UserLogin($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        echo "<div style='color:red;'>Please enter both username and password.</div>";
    } else {
        // รับค่าจากฟอร์ม
        $user->setUsername($_POST['username']);
        $user->setPassword($_POST['password']);

        error_log("Login process initiated. Username: {$_POST['username']}, Password: {$_POST['password']}");

        if ($user->login()) {
            header("Location: mail.php");
            exit;
        } else {
            echo "<div style='color:red;'>Incorrect username or password. Please try again.</div>";
        }
    }
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body>
    <div class="container">
        <h3 class="my-3">Login Page</h3>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Sign In</button>
            </div>
            <p class="mt-3">No account? <a href="signup.php">Create one!</a></p>
        </form>
    </div>
</body>
</html>
