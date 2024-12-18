<?php
session_start(); // เริ่มต้น session ที่นี่
ob_start();
include_once('config/Database.php');
include_once('class/UserLogin.php');
include_once('class/Utils.php');

// เชื่อมต่อฐานข้อมูล
$connectDB = new Database();
$db = $connectDB->getConnection();

// สร้าง Object UserLogin
$user = new UserLogin($db);
$bs = new Bootstrap();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        $bs->DisplayAlert("Please enter both username and password.", "danger");
    } else {
        // รับค่าจากฟอร์ม
        $user->setUsername($_POST['username']);
        $user->setPassword($_POST['password']);

        error_log("Login process initiated. Username: {$_POST['username']}, Password: {$_POST['password']}");

        // ตรวจสอบว่า username มีอยู่ในระบบหรือไม่
        if ($user->emailNotExists()) {
            $bs->DisplayAlert("User not found. Please check your Username or Password.", "danger");
        } else {
            // ตรวจสอบรหัสผ่าน
            $verifyResult = $user->verifyPassword();
            error_log("Password verification result: " . ($verifyResult ? 'true' : 'false'));

            if ($verifyResult === true) {
                header("Location: mail.php");
                exit;
            } else {
                $bs->DisplayAlert("Incorrect password. Please try again.", "danger");
            }
        }
    }
}

ob_end_flush();
?>

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
            <a href="index.php" class="btn btn-secondary">Go Back</a>
            <button type="submit" class="btn btn-primary">Sign In</button>
        </div>
        <p class="mt-3">No account? <a href="signup.php">Create one!</a></p>
    </form>
</div>
