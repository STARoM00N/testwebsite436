<?php

class Database {
    private $host = "tcp:projectcs436database.database.windows.net,1433"; // Azure SQL Server Host
    private $db_name = "ProjectCS436"; // Database Name
    private $username = "projectcs436"; // Database Username
    private $password = "cs436team@"; // Database Password (แทนที่ด้วยรหัสผ่านจริง)

    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // ตรวจสอบการใช้งานตัวแปรให้ถูกต้อง
            $dsn = "sqlsrv:server=$this->host;Database=$this->db_name";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Database connected successfully."; // Debugging
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
