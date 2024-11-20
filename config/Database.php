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
            // เพิ่มตัวเลือกการเชื่อมต่อ (รวม Timeout และ SSL)
            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // เปิดโหมดข้อผิดพลาด
                PDO::ATTR_TIMEOUT => 30, // เพิ่ม Timeout เป็น 30 วินาที
                PDO::SQLSRV_ATTR_ENCRYPT => true, // เปิดใช้งานการเข้ารหัส SSL
                PDO::SQLSRV_ATTR_TRUST_SERVER_CERTIFICATE => false // ปิดการเชื่อถือใบรับรองแบบ Local
            );

            $this->conn = new PDO(
                "sqlsrv:server=$this->host;Database=$this->db",
                $this->username,
                $this->password,
                $options
            );
            echo "Connection successful!";
        } catch (PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }

        return $this->conn;
    }
}
?>
