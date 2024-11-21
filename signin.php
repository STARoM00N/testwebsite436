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

<div style="width: 400px; margin: auto; padding: 20px; background-color: #f5f5f5; border-radius: 5px;">
    <h3>Login Page</h3>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <div>
            <label for="username">Username</label>
            <input type="text" name="username" id="username" placeholder="Enter your username" required style="width: 100%; padding: 10px; margin-bottom: 10px;">
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Enter your password" required style="width: 100%; padding: 10px; margin-bottom: 10px;">
        </div>
        <button type="submit" style="width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 5px;">Sign In</button>
    </form>
</div>
