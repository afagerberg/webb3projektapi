<?php
class Database{
  
    // specify your own database credentials
    private $host = "afagerberg.se.mysql";
    private $db_name = "afagerberg_sewebb3portfolio";
    private $username = "afagerberg_sewebb3portfolio";
    private $password = "portfoliodt173g";
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

    public function close(){
        $this->conn = null;
    }
}