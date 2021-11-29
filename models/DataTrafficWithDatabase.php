<?php


class DataTrafficWithDatabase
{
    private $server_name = "localhost";
    private $database_username = "root";
    private $database_password = "";
    private $database_name = "food";
    private $connection = null;

    public function getConnection(){
        return $this->connect();
    }

    private function connect(){
        if($this->connection == null){
            $this->connection = new mysqli($this->server_name,
                $this->database_username,
                $this->database_password,
                $this->database_name);
            return $this->connection;
        }
        else{
            return $this->connection;
        }
    }
}
?>