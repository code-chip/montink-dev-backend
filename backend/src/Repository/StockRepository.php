<?php

namespace App\Repository;

use App\Database\Connection;
use PDO;

class StockRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM stocks");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO stocks (product_id, variation, quantity) VALUES (:product_id, :variation, :quantity)");
        $stmt->execute([
            ':product_id' => $data['product_id'],
            ':variation' => $data['variation'],
            ':quantity' => $data['quantity'],
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE stocks SET quantity = :quantity WHERE id = :id");
        return $stmt->execute([
            ':id' => $data['id'],
            ':quantity' => $data['quantity'],
        ]);
    }
}
