<?php

class UserRegister {
    private $conn;
    private $table_name = "users";

    public $username;
    public $fname;
    public $lname;
    public $email;
    public $password;
    public $confirm_password;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function setFName($fname) { $this->fname = $fname; }
    public function setLName($lname) { $this->lname = $lname; }
    public function setEmail($email) { $this->email = $email; }
    public function setPassword($password) { $this->password = $password; }
    public function setConfirmPassword($confirm_password) { $this->confirm_password = $confirm_password; }
    public function setUsername($username) { $this->username = $username; }

    public function validatePassword() {
        return $this->password === $this->confirm_password;
    }

    public function checkPasswordLength() {
        return strlen($this->password) >= 6;
    }

    public function validateEmailFormat() {
        return filter_var($this->email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function checkUsername() {
        $query = "SELECT TOP 1 * FROM {$this->table_name} WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        return $stmt->rowCount() === 0;
    }

    public function validateUserInput() {
        return $this->checkPasswordLength() &&
               $this->validatePassword() &&
               $this->checkEmail() &&
               $this->validateEmailFormat() &&
               $this->checkUsername();
    }

    public function createUser() {
        $query = "INSERT INTO {$this->table_name} (username, email, password, firstname, lastname) 
                  VALUES (:username, :email, :password, :firstname, :lastname)";
        $stmt = $this->conn->prepare($query);
    
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);
    
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
    
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Insert error: " . $e->getMessage());
            return false;
        }
    }
       
    
    public function checkEmail() {
        $query = "SELECT TOP 1 * FROM {$this->table_name} WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        return $stmt->rowCount() === 0;
    }
}
?>
