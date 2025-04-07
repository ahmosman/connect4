<?php

namespace App;

use Dotenv\Dotenv;
use Exception;
use mysqli;

class Config
{
    private string $hostname;
    private ?string $port;
    private string $username;
    private string $password;
    private string $db;
    private mysqli $conn;

    public function __construct()
    {
        try {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
            $dotenv->load();
    
            $this->hostname = $_ENV['DB_HOSTNAME'];
            $this->username = $_ENV['DB_USERNAME'];
            $this->password = $_ENV['DB_PASSWORD'];
            $this->db = $_ENV['DB_NAME'];
            $this->port = $_ENV['DB_PORT'] ?? null;
    
            $this->conn = new mysqli(
                $this->hostname,
                $this->username,
                $this->password,
                $this->db,
                $this->port
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

