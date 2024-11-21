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

    public function emailNotExists() {
        $query = "SELECT id FROM {$this->table_name} WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $this->username);

        if (!$stmt->execute()) {
            error_log(json_encode($stmt->errorInfo())); // Log SQL Error
            die("Query failed.");
        }

        $rowCount = $stmt->rowCount();
        error_log(json_encode(['username' => $this->username, 'row_count' => $rowCount]));

        return $rowCount == 0;
    }

    public function login() {
        $query = "SELECT id, password FROM {$this->table_name} WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $this->username);
    
        if (!$stmt->execute()) {
            error_log(json_encode($stmt->errorInfo())); // Log SQL Error
            die("Query failed.");
        }
    
        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashedPassword = $row['password'];
    
            // Debugging Passwords
            error_log("Original password: {$this->password}");
            error_log("Hashed password from DB: {$hashedPassword}");
            error_log("Password verification result: " . (password_verify($this->password, $hashedPassword) ? 'true' : 'false'));
    
            if (password_verify($this->password, $hashedPassword)) {
                session_start();
                $_SESSION['userid'] = $row['id'];
                return true;
            } else {
                return false; // รหัสผ่านไม่ถูกต้อง
            }
        } else {
            return false; // ไม่มี Username นี้ในระบบ
        }
    }    
}

?>
