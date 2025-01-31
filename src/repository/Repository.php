<?php
namespace App\repository;

use App\services\Database;

require_once __DIR__.'/../services/Database.php';

class Repository{
    protected $database;

    public function __construct(){
        $this->database = new Database();
    }


}