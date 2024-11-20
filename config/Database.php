<?php
class Database {
    private $host = "tcp:prohectcs436database.database.windows.net,1433"; // Server name
    private $db = "ProjectCS436"; // Database name
    private $username = "projectcs436"; // Username
    private $password = ".cs436team"; // Replace with your actual password
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
?>
