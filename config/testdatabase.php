<?php
$server = "tcp:prohectcs436database.database.windows.net,1433";
$database = "ProjectCS436";
$username = "projectcs436";
$password = ".cs436team";

try {
    $conn = new PDO("sqlsrv:server=$server;Database=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connection successful!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
