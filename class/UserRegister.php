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
        $query = "SELECT TOP 1 * FROM {$this->table_name} WHERE Username = :username";
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
                  (Username, Email, Password, [FirstName], [LastName]) 
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
                // Redirect to the signin page after successful registration
                header("Location: signin.php");
                exit;
            } else {
                throw new Exception("Unable to execute query");
            }
        } catch (PDOException $e) {
            // Catch database-specific errors
            error_log("Database Error: " . $e->getMessage()); // Log error for debugging
            echo "<script>alert('Database error occurred. Please try again later.');</script>";
        } catch (Exception $e) {
            // Catch general PHP errors
            error_log("General Error: " . $e->getMessage());
            echo "<script>alert('An unexpected error occurred. Please try again later.');</script>";
        }
    }

    public function checkEmail() {
        $query = "SELECT TOP 1 * FROM {$this->table_name} WHERE Email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        return $stmt->rowCount() === 0;
    }
}
?>
