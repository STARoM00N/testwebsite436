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
        if (!$this->validateUserInput()) {
            echo "<script>alert('Validation failed. Check your input values.');</script>";
            return false;
        }
    
        $query = "INSERT INTO {$this->table_name} 
          (Username, Email, Password, FirstName, LastName) 
          VALUES (:username, :email, :password, :fname, :lname)";
        $stmt = $this->conn->prepare($query);
    
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
    
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":fname", $this->fname);
        $stmt->bindParam(":lname", $this->lname);

        try {
            if ($stmt->execute()) {
                echo "<script>alert('User created successfully! Redirecting to login page...');</script>";
                echo "<script>window.location.href = 'signin.php';</script>";
                exit;
            } else {
                throw new Exception("Unable to execute query");
            }
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger' role='alert'>Failed to create user: {$e->getMessage()}</div>";
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
