<?php

require_once __DIR__.'/../services/Database.php';

class Repository{
    protected $pdo;

    public function __construct(){
        $this->pdo = (new Database())->getConnection();
    }


}