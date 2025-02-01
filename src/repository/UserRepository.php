<?php

namespace App\repository;

use App\models\User;
use PDO;

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
            $user['id'],
            $user['username'],
            $user['email'],
            $user['password_hash']
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


    // Musze to zostawic, bo sie psuje bez tej funkcji
    public function getUserStats(int $userId): ?object {
        $stmt = $this->database->connect()->prepare('
            SELECT total_finalized_lists, total_spent
            FROM user_stats
            WHERE user_id = :user_id
        ');
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $stats = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$stats) {
            return (object)[
                'total_finalized_lists' => 0,
                'total_spent' => 0.00
            ];
        }
        return (object)$stats;
    }

    public function updatePassword(int $userId, string $newHashedPassword): void {
        $stmt = $this->database->connect()->prepare('
            UPDATE users
            SET password_hash = :password
            WHERE id = :user_id
        ');
        $stmt->bindParam(':password', $newHashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function deleteUser(int $userId): void {
        $stmt = $this->database->connect()->prepare('
            DELETE FROM users
            WHERE id = :user_id
        ');
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }
}
