<?php
class Config
{
    private string $hostname;
    private string $username;
    private string $password;
    private string $db;
    private mysqli $conn;
    public function __construct(){
        $this->hostname = "localhost";
        $this->username = "root";
        $this->password = "";
        $this->db = "game_competitive";
        $this->conn = new mysqli($this->hostname,$this->username,$this->password,$this->db);
    }
    public function getConn()
    {
        return $this->conn;
    }
}

