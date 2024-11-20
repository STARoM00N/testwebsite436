<?php
class Database {
    private $host = "tcp:prohectcs436database.database.windows.net,1433"; // Azure SQL Server Host
    private $db = "ProjectCS436"; // Database Name
    private $username = "projectcs436"; // Database Username
    private $password = ".cs436team"; // Database Password (แทนที่ด้วยรหัสผ่านจริงของคุณ)
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "sqlsrv:server=$this->host;Database=$this->db",
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
