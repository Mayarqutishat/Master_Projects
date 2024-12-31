<?php
class Connection {
    protected $server = "localhost";
    protected $username = "root";
    protected $password = "";
    protected $dbname = "ecommerce_db";
    protected $dsn;
    public $dbconnection;

    public function connect() {
        try {
            $this->dsn = "mysql:host=$this->server;dbname=$this->dbname";
            $this->dbconnection = new PDO($this->dsn, $this->username, $this->password);
            echo "Database: Connected";
        } catch (PDOException $error) {
            echo $error;
        }
    }
}
?>