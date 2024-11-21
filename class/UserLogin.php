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
            var_dump($stmt->errorInfo());
            die("Query failed.");
        }

        $rowCount = $stmt->rowCount();
        return $rowCount == 0;
    }

    public function verifyPassword() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $query = "SELECT id, password FROM {$this->table_name} WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $this->username);

        if (!$stmt->execute()) {
            var_dump($stmt->errorInfo());
            die("Query failed.");
        }

        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashedPassword = $row['password'];

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
