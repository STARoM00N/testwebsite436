<?php
class Database {
    private $host = "tcp:prohtectcs436database.database.windows.net,1433"; // ชื่อเซิร์ฟเวอร์
    private $db = "ProjectCS436"; // ชื่อฐานข้อมูล
    private $username = "projectcs436"; // ชื่อผู้ใช้
    private $password = ".cs436team"; // รหัสผ่าน
    public $conn;

    class Database{
        private $host = "localhost";
        private $db = "login_regis_db";
        private $username = "root";
        private $password = "";
        public $conn;
    public function getConnection() {
        $this->conn = null;

        public function getConnection(){
            $this -> conn = null;

            try{
                $this->conn = new PDO("mysql:host=".$this->host. ";dbname=". $this->db, $this->username, $this->password);
            }
            catch(PDOException $exception){
                echo "Connection Error: ". $exception->getMessage();
            }

            return $this->conn;
        try {
            $this->conn = new PDO(
                "sqlsrv:server=$this->host;Database=$this->db",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }
    }

?>
        return $this->conn;
    }
}
?>