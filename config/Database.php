<?php
class Database {
    private $host = "tcp:prohtectcs436database.database.windows.net,1433"; // ชื่อเซิร์ฟเวอร์
    private $db = "ProjectCS436"; // ชื่อฐานข้อมูล
    private $username = "projectcs436"; // ชื่อผู้ใช้
    private $password = ".cs436team"; // รหัสผ่าน
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "sqlsrv:server=$this->host;Database=$this->db;ConnectionTimeout=30",
                $this->username,
                $this->password
            );                      
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connection successful!";
        } catch (PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }

        return $this->conn;
    }
}

// ทดสอบการเชื่อมต่อ
$db = new Database();
$conn = $db->getConnection();
?>
