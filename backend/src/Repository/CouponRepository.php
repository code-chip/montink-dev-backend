<?php

namespace App\Repository;

use App\Database\Connection;
use PDO;

class CouponRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM coupons");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO coupons (code, discount, min_value, expires_at) VALUES (:code, :discount, :min_value, :expires_at)");
        $stmt->execute([
            ':code' => $data['code'],
            ':discount' => $data['discount'],
            ':min_value' => $data['min_value'],
            ':expires_at' => $data['expires_at'],
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function getByCode(string $code): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM coupons WHERE code = :code");
        $stmt->execute([':code' => $code]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
