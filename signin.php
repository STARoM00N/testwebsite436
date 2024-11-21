<?php
ob_start();
include_once('config/Database.php');
include_once('class/UserLogin.php');
include_once('class/Utils.php');

$connectDB = new Database();
$db = $connectDB->getConnection();

$user = new UserLogin($db);
$bs = new Bootstrap();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
ob_end_flush();
?>
