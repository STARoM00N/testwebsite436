<?php
class Database {
    private $host = "prohectcs436database.database.windows.net"; // Host ของ SQL Server
    private $db_name = "ProjectCS436"; // ชื่อฐานข้อมูล
    private $username = "projectcs436"; // Username
    private $password = ".cs436team"; // Password
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "sqlsrv:Server=" . $this->host . ";Database=" . $this->db_name, 
                $this->username, 
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ตั้งค่า Error Mode
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
