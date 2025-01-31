<?php
namespace App\repository;

use App\models\ShoppingList;
use PDO;
use App\repository\Repository;

require_once 'Repository.php';
require_once __DIR__.'/../models/ShoppingList.php';

class ListRepository extends Repository {


    public function getListsByUser(int $userId): array {
        $stmt = $this->database->connect()->prepare('SELECT * FROM vw_all_lists_with_users WHERE user_id = :user_id ORDER BY created_at DESC');
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $lists = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $lists[] = new ShoppingList(
                (int)$row['list_id'],
                (int)$row['user_id'],
                $row['list_name'],
                $row['status'],
                $row['created_at'],
                $row['updated_at'],
                (float)$row['total_cost']
            );
        }
        return $lists;
    }


    public function createList(int $userId, string $listName): void {
        $stmt = $this->database->connect()->prepare('INSERT INTO shopping_lists (user_id, list_name) VALUES (:user_id, :list_name)');
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':list_name', $listName, PDO::PARAM_STR);
        $stmt->execute();
    }


    public function deleteList(int $listId): void {
        $stmt = $this->database->connect()->prepare('DELETE FROM shopping_lists WHERE id = :list_id');
        $stmt->bindParam(':list_id', $listId, PDO::PARAM_INT);
        $stmt->execute();
    }


    public function finalizeList(int $listId): void {
        $stmt = $this->database->connect()->prepare('UPDATE shopping_lists SET status = :status WHERE id = :list_id');
        $status = 'finalized';
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':list_id', $listId, PDO::PARAM_INT);
        $stmt->execute();
    }


    public function getListById(int $listId): ?ShoppingList {
        $stmt = $this->database->connect()->prepare('SELECT * FROM vw_all_lists_with_users WHERE list_id = :list_id');
        $stmt->bindParam(':list_id', $listId, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        return new ShoppingList(
            (int)$row['list_id'],
            (int)$row['user_id'],
            $row['list_name'],
            $row['status'],
            $row['created_at'],
            $row['updated_at'],
            (float)$row['total_cost']
        );
    }

    public function getLastInsertId(): ?int {
        return (int)$this->database->connect()->lastInsertId('shopping_lists_id_seq');
    }
}
