<?php
namespace App\models;

class ShoppingList
{
    private int $id;
    private int $user_id;
    private string $list_name;
    private string $status;
    private string $created_at;
    private string $updated_at;
    private float $total_cost;

    public function __construct(int $id, int $user_id, string $list_name, string $status, string $created_at, string $updated_at, float $total_cost)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->list_name = $list_name;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->total_cost = $total_cost;
    }

    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->user_id; }
    public function getListName(): string { return $this->list_name; }
    public function getStatus(): string { return $this->status; }
    public function getCreatedAt(): string { return $this->created_at; }
    public function getUpdatedAt(): string { return $this->updated_at; }
    public function getTotalCost(): float { return $this->total_cost; }

}
