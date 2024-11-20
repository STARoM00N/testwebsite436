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
        $this->username = trim($username); // ตัดช่องว่างที่ไม่จำเป็นออก
    }

    public function setPassword($password) {
        $this->password = trim($password); // ตัดช่องว่างที่ไม่จำเป็นออก
    }

    public function emailNotExists() {
        $query = "SELECT id FROM {$this->table_name} WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();
    
        // Debugging
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        var_dump([
            'username_input' => $this->username,
            'query_result' => $result,
            'row_count' => $stmt->rowCount()
        ]);
    
        return $stmt->rowCount() == 0; // ถ้าไม่มีผลลัพธ์ -> username ไม่พบ
    }
    
    public function verifyPassword() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    
        $query = "SELECT id, password FROM {$this->table_name} WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();
    
        // Debugging
        var_dump([
            'username_input' => $this->username,
            'query_result' => $stmt->fetch(PDO::FETCH_ASSOC),
            'row_count' => $stmt->rowCount()
        ]);
    
        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashedPassword = $row['password'];
    
            // Debugging
            var_dump([
                'input_password' => $this->password,
                'hashed_password' => $hashedPassword,
                'password_verify' => password_verify($this->password, $hashedPassword)
            ]);
    
            if (password_verify($this->password, $hashedPassword)) {
                $_SESSION['userid'] = $row['id'];
                header("Location: mail.php");
                exit;
            } else {
                echo "<script>alert('Invalid username or password.');</script>";
                return false;
            }
        } else {
            echo "<script>alert('User not found.');</script>";
            return false;
        }
    }
      
    public function logOut() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_unset(); // ลบ session ทั้งหมด
        session_destroy(); // ทำลาย session
        header("Location: signin.php");
        exit;
    }

    public function userData($userid) {
        $query = "SELECT * FROM {$this->table_name} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $userid);
        $stmt->execute();

        return $stmt->rowCount() > 0 ? $stmt->fetch(PDO::FETCH_ASSOC) : false;
    }
}
?>
