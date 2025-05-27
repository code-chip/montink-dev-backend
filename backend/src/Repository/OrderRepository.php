<?php

namespace App\Repository;

use App\Database\Connection;
use PDO;

class OrderRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO orders (total, status, address, items) VALUES (:total, :status, :address, :items)");
        $stmt->execute([
            ':total' => $data['total'],
            ':status' => $data['status'],
            ':address' => $data['address'],
            ':items' => $data['items'],
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM orders WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    public function updateStatus(int $id, string $status): void
    {
        $stmt = $this->db->prepare("UPDATE orders SET status = :status WHERE id = :id");
        $stmt->execute([
            ':id' => $id,
            ':status' => $status,
        ]);
    }
}
