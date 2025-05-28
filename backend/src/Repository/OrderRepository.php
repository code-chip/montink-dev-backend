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

    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT 
                o.*, 
                op.product_id, 
                op.quantity, 
                op.unit_price,
                p.name AS product_name,
                (op.quantity * op.unit_price) AS price_pay
            FROM orders o
            LEFT JOIN order_products op ON op.order_id = o.id
            LEFT JOIN products p ON p.id = op.product_id
        ");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $orderProduct = [];

        foreach ($rows as $row) {
            $orderId = $row['id'];

            if (!isset($orderProduct[$orderId])) {
                $orderProduct[$orderId] = [
                    'id' => $row['id'],
                    'status' => $row['status'],
                    'total' => $row['total'],
                    'shipping' => $row['shipping'],
                    'customer_email' => $row['customer_email'],
                    'customer_address' => $row['customer_address'],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at'],
                    'products' => [],
                ];
            }

            if ($row['product_id']) {
                $orderProduct[$orderId]['products'][] = [
                    'id' => $row['product_id'],
                    'name' => $row['product_name'],
                    'quantity' => $row['quantity'],
                    'unit_price' => $row['unit_price'],
                    'pay_price' => $row['price_pay'],
                ];
            }
        }

        return array_values($orderProduct);

    }

    public function create(array $data): int
    {
        $this->db->beginTransaction();

        $stmt = $this->db->prepare("INSERT INTO orders (status, total, shipping, customer_address) VALUES (:status, :total, :shipping, :address)");
        $stmt->execute([
            ':status' => $data['status'],
            ':total' => (float)$data['total'],
            ':shipping' => (float)$data['shipping'],
            ':address' => $data['address']
        ]);
        
        $orderId = (int)$this->db->lastInsertId();

        // Insere produtos relacionados na tabela order_products
        $stmtProduct = $this->db->prepare("
            INSERT INTO order_products (order_id, product_id, quantity, unit_price)
            VALUES (:order_id, :product_id, :quantity, :unit_price)
        ");

        foreach ($data['products'] as $product) {
            $stmtProduct->execute([
                ':order_id'   => $orderId,
                ':product_id' => $product['id'],
                ':quantity'   => $product['quantity'],
                ':unit_price' => $product['price']
            ]);
        }

        // Confirma transação
        $this->db->commit();

        return $orderId;
    }

    public function create2(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO orders (status, total, shipping, address) VALUES (:status, :total, :shipping, :address)");
        $stmt->execute([
            ':total' => $data['total'],
            ':status' => $data['status'],
            ':address' => $data['address'],
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
