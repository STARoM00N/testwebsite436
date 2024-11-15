<?php

class UserRegister{
    private $conn;
    private $table_name = "users";

    public $username;
    public $fname;
    public $lname;
    public $email;
    public $password;
    public $confirm_password;

    public function __construct($db){
        $this->conn = $db;
    }

    // การตั้งค่าข้อมูลต่าง ๆ
    public function setFName($fname){ $this->fname = $fname; }
    public function setLName($lname){ $this->lname = $lname; }
    public function setEmail($email){ $this->email = $email; }
    public function setPassword($password){ $this->password = $password; }
    public function setConfirmPassword($confirm_password){ $this->confirm_password = $confirm_password; }
    public function setUsername($username){ $this->username = $username; }

    // ตรวจสอบว่าใส่รหัสผ่านตรงกัน
    public function validatePassword(){
        return $this->password === $this->confirm_password;
    }

    // ตรวจสอบความยาวรหัสผ่าน
    public function checkPasswordLength(){
        return strlen($this->password) >= 6;
    }

    // ตรวจสอบรูปแบบอีเมล
    public function validateEmailFormat(){
        return filter_var($this->email, FILTER_VALIDATE_EMAIL) !== false;
    }

    // ตรวจสอบชื่อผู้ใช้ไม่ซ้ำกัน
    public function checkUsername(){
        $query = "SELECT * FROM {$this->table_name} WHERE Username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        return $stmt->rowCount() === 0;
    }

    // ตรวจสอบเงื่อนไขการสมัคร
    public function validateUserInput(){
        return $this->checkPasswordLength() &&
               $this->validatePassword() &&
               $this->checkEmail() &&
               $this->validateEmailFormat() &&
               $this->checkUsername();
    }

    public function createUser() {
        if (!$this->validateUserInput()) {
            echo "<div class='alert alert-danger' role='alert'>Validation failed. Check your input values.</div>";
            return false;
        }
    
        $query = "INSERT INTO {$this->table_name} (Username, Email, Password, `First Name`, `Last Name`) VALUES (:username, :email, :password, :fname, :lname)";
        $stmt = $this->conn->prepare($query);
    
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->fname = htmlspecialchars(strip_tags($this->fname));
        $this->lname = htmlspecialchars(strip_tags($this->lname));
    
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
    
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":fname", $this->fname);
        $stmt->bindParam(":lname", $this->lname);
        $stmt->bindParam(":password", $hashedPassword);
    
        try {
            if ($stmt->execute()) {
                echo "<div class='alert alert-success' role='alert'>User created successfully.</div>";
                return true;
            } else {
                $errorInfo = $stmt->errorInfo();
                echo "<div class='alert alert-danger' role='alert'>Failed to create user: " . $errorInfo[2] . "</div>";
                return false;
            }
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger' role='alert'>Exception occurred: " . $e->getMessage() . "</div>";
            return false;
        }
    }

    // ตรวจสอบอีเมลไม่ซ้ำกัน
    public function checkEmail() {
        $query = "SELECT * FROM {$this->table_name} WHERE Email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        return $stmt->rowCount() === 0;
    }
   
}

?>