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
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            if ($_SESSION['env'] == 'prod') {
                $this->hostname = get_cfg_var("db.hostname");
                $this->username = get_cfg_var("db.username");
                $this->password = get_cfg_var("db.password");
            } else {
                $this->hostname = "localhost";
                $this->username = "root";
                $this->password = "";
            }
            $this->db = "connect4";
            $this->conn = new mysqli(
                $this->hostname,
                $this->username,
                $this->password,
                $this->db
            );
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

