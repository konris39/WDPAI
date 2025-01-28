<?php

class Database {
    private $pdo;

    public function __construct() {
        $host = "db"; // Nazwa usługi bazy danych w docker-compose.yml
        $port = "5432";
        $dbname = "wdpai";
        $user = "postgres";
        $password = "postgres";

        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

        try {
            $this->pdo = new PDO($dsn, $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Błąd połączenia z bazą danych: " . $e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
