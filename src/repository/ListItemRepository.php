<?php
namespace App\repository;

use App\models\ListItem;
use PDO;
use App\repository\Repository;

require_once 'Repository.php';
require_once __DIR__.'/../models/ListItem.php';

class ListItemRepository extends Repository {

    public function getItemsByList(int $listId): array {
        $stmt = $this->database->connect()->prepare('SELECT * FROM shopping_list_items WHERE shopping_list_id = :list_id ORDER BY id ASC');
        $stmt->bindParam(':list_id', $listId, PDO::PARAM_INT);
        $stmt->execute();

        $items = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = new ListItem(
                (int)$row['id'],
                (int)$row['shopping_list_id'],
                $row['item_name'],
                (int)$row['quantity'],
                (float)$row['price']
            );
        }
        return $items;
    }

    public function getItemById(int $itemId): ?ListItem {
        $stmt = $this->database->connect()->prepare('SELECT * FROM shopping_list_items WHERE id = :item_id');
        $stmt->bindParam(':item_id', $itemId, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return new ListItem(
            (int)$row['id'],
            (int)$row['shopping_list_id'],
            $row['item_name'],
            (int)$row['quantity'],
            (float)$row['price']
        );
    }

    public function addItem(int $listId, string $itemName, int $quantity, float $price): void {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO shopping_list_items (shopping_list_id, item_name, quantity, price)
            VALUES (:list_id, :item_name, :quantity, :price)
        ');
        $stmt->bindParam(':list_id', $listId, PDO::PARAM_INT);
        $stmt->bindParam(':item_name', $itemName, PDO::PARAM_STR);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function deleteItem(int $itemId): void {
        $stmt = $this->database->connect()->prepare('DELETE FROM shopping_list_items WHERE id = :item_id');
        $stmt->bindParam(':item_id', $itemId, PDO::PARAM_INT);
        $stmt->execute();
    }

}
