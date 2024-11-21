<?php
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
        echo "<p style='color: red;'>Incorrect username or password. Please try again.</p>";
    }
}
?>

<form method="POST" action="">
    <label>Username:</label>
    <input type="text" name="username" required>
    <label>Password:</label>
    <input type="password" name="password" required>
    <button type="submit">Sign In</button>
</form>
