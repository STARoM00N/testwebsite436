<?php

class UserLogin {
    private $conn;
    private $table_name = "users";

    public $username;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function setUsername($username) {
        $this->username = trim($username);
    }

    public function setPassword($password) {
        $this->password = trim($password);
    }

    public function login() {
        $query = "SELECT id, password FROM {$this->table_name} WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $this->username);

        // Execute query
        if (!$stmt->execute()) {
            error_log(json_encode($stmt->errorInfo())); // Log SQL Error
            die("Query failed.");
        }

        // Check if username exists
        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashedPassword = $row['password'];

            // Debugging
            error_log("Input password: {$this->password}");
            error_log("Hashed password: {$hashedPassword}");

            // Verify password
            if (password_verify($this->password, $hashedPassword)) {
                // Start session if not already started
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                // Set session and return success
                $_SESSION['userid'] = $row['id'];
                error_log("Login successful for user: {$this->username}");
                return true;
            } else {
                error_log("Password did not match for user: {$this->username}");
                return false; // Incorrect password
            }
        } else {
            error_log("User not found: {$this->username}");
            return false; // Username not found
        }
    }
}

?>
