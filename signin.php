<?php
ob_start();
include_once('config/Database.php');
include_once('class/UserLogin.php');

$connectDB = new Database();
$db = $connectDB->getConnection();
$user = new UserLogin($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user->setUsername($_POST['username']);
    $user->setPassword($_POST['password']);

    if ($user->login()) {
        header("Location: mail.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Incorrect username or password. Please try again.</div>";
    }
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <label>Username:</label>
        <input type="text" name="username" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <button type="submit">Sign In</button>
    </form>
</body>
</html>
