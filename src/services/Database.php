<?php

class Database {
    //private $pdo;
    private string $host;
    private string $dbname;
    private string $user;
    private string $password;

    public function __construct() {
        $this->host = "db";
        $this->dbname = "wdpai_db";
        $this->user = "userPostgres";
        $this->password = "password";

    }

    public function connect() {
        try {
            $conn = new PDO(
                "pgsql:host=db;port=5432;dbname=post_db",
                "userPostgres",
                "password"
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn; // Usuń echo!
        } catch (PDOException $e) {
            die("Błąd połączenia: " . $e->getMessage());
        }
    }

}
