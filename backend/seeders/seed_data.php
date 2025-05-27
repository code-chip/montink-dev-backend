<?php
require_once __DIR__ . '/../src/Database/Connection.php';

use App\Database\Connection;

$pdo = (new Connection())->getConnection();

$pdo->exec("INSERT INTO products (name, price) VALUES 
    ('T-Shirt', 59.90), 
    ('Sneakers', 199.90)");

$pdo->exec("INSERT INTO stocks (product_id, variation, quantity) VALUES 
    (1, 'Size M', 10),
    (1, 'Size L', 5),
    (2, 'Size 42', 7)");

$pdo->exec("INSERT INTO coupons (code, discount, min_value, expires_at) VALUES 
    ('WELCOME10', 10.00, 100.00, DATE_ADD(NOW(), INTERVAL 30 DAY))");

echo "Seed data inserted successfully.\n";
