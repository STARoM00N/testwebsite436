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

        try {
            $stmt->execute();
            if ($stmt->rowCount() === 1) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $hashedPassword = $row['password'];

                error_log("Input Password: {$this->password}");
                error_log("Hashed Password from DB: {$hashedPassword}");

                if (password_verify($this->password, $hashedPassword)) {
                    session_start();
                    $_SESSION['userid'] = $row['id'];
                    return true;
                } else {
                    error_log("Incorrect password for user: {$this->username}");
                    return false;
                }
            }
            error_log("User not found: {$this->username}");
            return false;
        } catch (PDOException $e) {
            error_log("Query error: " . $e->getMessage());
            return false;
        }
    }
}
?>
