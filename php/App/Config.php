<?php

namespace App;

use Exception;
use mysqli;

class Config
{
    private string $hostname;
    private string $username;
    private string $password;
    private string $db;
    private mysqli $conn;

    public function __construct()
    {
        $this->hostname = "localhost";
        $this->username = "root";
        $this->password = "";
        $this->db = "connect4";
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $this->conn = new mysqli($this->hostname, $this->username, $this->password, $this->db);
            $this->conn->set_charset("utf8mb4");
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit('Wystąpił błąd');
        }
    }

    public function getConn(): mysqli
    {
        return $this->conn;
    }
}

