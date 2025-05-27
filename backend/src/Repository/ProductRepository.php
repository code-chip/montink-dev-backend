<?php

namespace App\Repository;

use App\Database\Connection;
use PDO;

class ProductRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM products");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO products (name, price) VALUES (:name, :price)");
        $stmt->execute([
            ':name' => $data['name'],
            ':price' => $data['price'],
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE products SET name = :name, price = :price WHERE id = :id");
        return $stmt->execute([
            ':id' => $data['id'],
            ':name' => $data['name'],
            ':price' => $data['price'],
        ]);
    }
}
