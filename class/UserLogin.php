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
    
        if (!$stmt->execute()) {
            error_log("Query failed: " . json_encode($stmt->errorInfo()));
            die("Database query failed!");
        }
    
        if ($stmt->rowCount() === 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashedPassword = $row['password'];
    
            // Log ข้อมูลเพื่อ debug
            error_log("Original password: {$this->password}");
            error_log("Hashed password from DB: {$hashedPassword}");
            error_log("Password verify result: " . (password_verify($this->password, $hashedPassword) ? 'true' : 'false'));
    
            if (password_verify($this->password, $hashedPassword)) {
                session_start();
                $_SESSION['userid'] = $row['id'];
                return true;
            } else {
                error_log("Incorrect password for user {$this->username}");
                return false;
            }
        }
        return false;
    }
    
}
?>
