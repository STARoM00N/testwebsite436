<?php
class Database {
    private $host = "prohectcs436database.database.windows.net";
    private $db_name = "ProjectCS436";
    private $username = "projectcs436";
    private $password = ".cs436team";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("sqlsrv:Server=" . $this->host . ";Database=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            error_log("Connection error: " . $exception->getMessage());
            die("Database connection failed.");
        }

        return $this->conn;
    }
}
?>
