<?php

namespace App\repository;

use App\models\User;
use PDO;
use Repository;

require_once 'Repository.php';
require_once __DIR__.'/../models/User.php';

class UserRepository extends Repository {

    public function getUser(string $email): ?User {
        $stmt = $this->database->connect()->prepare('SELECT * FROM public.users WHERE email = :email');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return null;
        }

        return new User(
            $user['username'],
            $user['email'],
            $user['password_hash'] // Hasło nadal przechowujemy jako zwykły string
        );
    }

    public function createUser(string $username, string $email, string $password): void {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password)
        ');

        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
    }
}
