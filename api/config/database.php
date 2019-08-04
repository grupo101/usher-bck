<?php
class Database{
 
    // specify your own database credentials
    private $host = "usher.sytes.net";
    private $db_name = "usher_web";
    private $username = "usher";
    private $password = "usher101";
    public $conn;
 
    // get the database connection
    public function getConnection(){
 
        $this->conn = null;
 
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }
}