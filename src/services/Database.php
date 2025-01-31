<?php
// WDPAI\src\services\Database.php

namespace App\services;

use PDO;
use PDOException;

class Database {
    private ?PDO $conn = null;
    private string $host;
    private string $dbname;
    private string $user;
    private string $password;

    public function __construct() {
        $this->host = "db";
        $this->dbname = "post_db";
        $this->user = "userPostgres";
        $this->password = "password";
    }

    public function connect(): PDO {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO(
                    "pgsql:host={$this->host};port=5432;dbname={$this->dbname}",
                    $this->user,
                    $this->password
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Błąd połączenia: " . $e->getMessage());
            }
        }
        return $this->conn;
    }
}
