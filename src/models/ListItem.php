<?php
namespace App\models;

class ListItem
{
    private int $id;
    private int $shopping_list_id;
    private string $item_name;
    private int $quantity;
    private float $price;

    public function __construct(int $id, int $shopping_list_id, string $item_name, int $quantity, float $price)
    {
        $this->id = $id;
        $this->shopping_list_id = $shopping_list_id;
        $this->item_name = $item_name;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    public function getId(): int { return $this->id; }
    public function getShoppingListId(): int { return $this->shopping_list_id; }
    public function getItemName(): string { return $this->item_name; }
    public function getQuantity(): int { return $this->quantity; }
    public function getPrice(): float { return $this->price; }

}
