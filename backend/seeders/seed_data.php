<?php
require_once __DIR__ . '/../src/Database/Connection.php';

use App\Database\Connection;

$pdo = Connection::getInstance();

$pdo->exec("INSERT INTO products (name, price) VALUES 
    ('T-Shirt', 59.90), 
    ('Sneakers', 199.90)");

$pdo->exec("INSERT INTO stocks (product_id, variation, quantity) VALUES 
    (1, 'Size M', 10),
    (1, 'Size L', 5),
    (2, 'Size 42', 7)");

$pdo->exec("INSERT INTO coupons (code, discount, min_value, expires_at) VALUES 
    ('WELCOME10', 10.00, 100.00, DATE_ADD(NOW(), INTERVAL 30 DAY))");

$pdo->exec("INSERT INTO orders (status, total, shipping, customer_email, customer_address) VALUES 
    ('completed', 259.80, 20.00, 'cliente1@example.com', 'Rua Exemplo, 123, São Paulo - SP'),
    ('pending', 199.90, 15.00, 'cliente2@example.com', 'Avenida Teste, 456, Rio de Janeiro - RJ')");

$pdo->exec("INSERT INTO order_products (order_id, product_id, quantity, unit_price) VALUES 
    (1, 1, 3, 59.90),
    (1, 2, 1, 199.90),
    (2, 2, 1, 199.90)
");

echo "Seed data inserted successfully.\n";
