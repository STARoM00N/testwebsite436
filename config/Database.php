
<?php
class Database {
    private $host = "tcp:prohectcs436database.database.windows.net,1433"; // Database server name
    private $db = "ProjectCS436"; // Database name
    private $username = "projectcs436"; // Username
    private $password = ".cs436team"; // Password
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "sqlsrv:server=$this->host;Database=$this->db",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_TIMEOUT => 60 // Set timeout to 60 seconds
                ]
            );
            echo "Connection successful!";
        } catch (PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }

        return $this->conn;
    }
}

// Testing database connection
$db = new Database();
$conn = $db->getConnection();
?>
