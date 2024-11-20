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
            $dsn = "sqlsrv:server=$this->host;database=$this->db";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::SQLSRV_ATTR_DIRECT_QUERY, true); // รองรับ rowCount
            echo "Database connected successfully."; // Debugging
        } catch (PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
            die();
        }

        return $this->conn;
    }
}
?>
