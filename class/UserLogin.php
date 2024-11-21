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
        // Debug ข้อมูล username และจำนวนแถว
        error_log(json_encode(['username' => $this->username, 'row_count' => $rowCount]));

        return $rowCount == 0; // ถ้าไม่มีผลลัพธ์ -> username ไม่พบ
    }

    public function verifyPassword() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
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
    
            // Debugging ขั้นตอนตรวจสอบรหัสผ่าน
            error_log("Input password: {$this->password}");
            error_log("Hashed password from DB: {$hashedPassword}");
            error_log("Password verify result: " . (password_verify($this->password, $hashedPassword) ? 'true' : 'false'));
            
            $storedPassword = '$2y$10$QGdzfZc51GM4lgCfyKuFuz2OZLvjkPx8bVTpONwL1Ho0B1R1pFcFe'; // ค่าจริงจากฐานข้อมูล (ตัวอย่าง)
            $inputPassword = '111111'; // รหัสผ่านที่ผู้ใช้กรอก
            if (password_verify($inputPassword, $storedPassword)) {
                echo 'Password matched!';
            } else {
                echo 'Password did not match!';
            }
        } else {
            return false; // ผู้ใช้ไม่พบ
        }
    }     

    public function logOut() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header("Location: signin.php");
        exit;
    }
}
?>
