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

        if (!$stmt->execute()) {
            var_dump($stmt->errorInfo()); // Debug SQL Error
            die("Query failed.");
        }

        $rowCount = $stmt->rowCount();
        var_dump(['username' => $this->username, 'row_count' => $rowCount]); // Debug

        return $rowCount == 0; // ถ้าไม่มีผลลัพธ์ -> username ไม่พบ
    }

    public function verifyPassword() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    
        $query = "SELECT id, password FROM {$this->table_name} WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $this->username);
    
        // Debugging ขณะ Execute
        if (!$stmt->execute()) {
            var_dump($stmt->errorInfo()); // แสดงรายละเอียด Error
            die("Query failed.");
        }
    
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
                return true;
            } else {
                echo "<script>alert('Incorrect password.');</script>";
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
        session_unset();
        session_destroy();
        header("Location: signin.php");
        exit;
    }
}
?>
