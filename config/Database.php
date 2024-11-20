<?php
class Database {
    private $host = "tcp:prohectcs436database.database.windows.net,1433"; // Azure SQL Server Host
    private $db = "ProjectCS436"; // Database Name
    private $username = "projectcs436"; // Database Username
    private $password = ".cs436team"; // Database Password
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // เชื่อมต่อกับ Azure SQL Server
            $dsn = "sqlsrv:server=$this->host;database=$this->db"; // ใช้ "database" พิมพ์เล็ก
            $this->conn = new PDO($dsn, $this->username, $this->password);

            // ตั้งค่า Error Mode
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Debug: แสดงข้อความเมื่อเชื่อมต่อสำเร็จ
            echo "Database connected successfully.";
        } catch (PDOException $e) {
            // แสดงข้อผิดพลาดเมื่อเชื่อมต่อไม่ได้
            echo "Connection Error: " . $e->getMessage();
        }

        return $this->conn;
    }
}

// ทดสอบการเชื่อมต่อ
$db = new Database();
$conn = $db->getConnection();
if ($conn) {
    echo "Connected to the database.";
} else {
    echo "Failed to connect to the database.";
}
?>