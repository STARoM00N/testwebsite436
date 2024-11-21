<?php
class Database {
    private $host = "tcp:prohectcs436database.database.windows.net,1433"; // เปลี่ยนเป็น Host ของ Azure
    private $db_name = "ProjectCS436"; // ชื่อฐานข้อมูล
    private $username = "projectcs436"; // Username
    private $password = ".cs436team"; // Password
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
