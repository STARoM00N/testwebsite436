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
    $user->setUsername($_POST['username']);
    $user->setPassword($_POST['password']);

    // เรียกใช้งานฟังก์ชัน login
    if ($user->login()) {
        // ล็อกอินสำเร็จ
        header("Location: mail.php");
        exit;
    } else {
        // ล็อกอินไม่สำเร็จ
        echo "<div class='alert alert-danger' role='alert'>Incorrect username or password. Please try again.</div>";
    }
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="page/style_sign.css">
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
                <a href="index.php" class="btn btn-secondary">Go Back</a>
                <button type="submit" class="btn btn-primary">Sign In</button>
            </div>
            <p class="mt-3">No account? <a href="signup.php">Create one!</a></p>
        </form>
    </div>
</body>
</html>
