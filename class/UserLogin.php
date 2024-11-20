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
        $this->username = $username;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function emailNotExists() {
        $query = "SELECT TOP 1 id FROM {$this->table_name} WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();

        return $stmt->rowCount() == 0;
    }

    public function verifyPassword() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    
        $query = "SELECT TOP 1 id, password FROM {$this->table_name} WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();
    
        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashedPassword = $row['password'];
    
            if (password_verify($this->password, $hashedPassword)) {
                $_SESSION['userid'] = $row['id'];
                header("Location: mail.php");
                exit;
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
        unset($_SESSION['userid']);
        unset($_SESSION['email']);
        header("Location: signin.php");
        exit;
    }    

    public function userData($userid) {
        $query = "SELECT * FROM {$this->table_name} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $userid);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user;
        } else {
            return false;
        }
    }
}
?>
