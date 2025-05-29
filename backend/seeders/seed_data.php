<?php
require_once __DIR__ . '/../src/Database/Connection.php';

use App\Database\Connection;

$pdo = Connection::getInstance();

$pdo->exec("INSERT INTO products (name, price) VALUES 
    ('T-Shirt', 59.90), 
    ('Sneakers', 199.90)");

$pdo->exec("INSERT INTO stocks (product_id, variation, quantity) VALUES 
    (1, 'Size M', 100),
    (1, 'Size L', 58),
    (2, 'Size 42', 7)");

$pdo->exec("INSERT INTO coupons (code, discount, min_value, expires_at) VALUES 
    ('WELCOME10', 10.00, 0, DATE_SUB(NOW(), INTERVAL 30 DAY)),
    ('PROMO50', 50.00, 50.00, DATE_ADD(NOW(), INTERVAL 180 DAY)),
    ('VIP100', 100.00, 150.00, DATE_ADD(NOW(), INTERVAL 2 YEAR))");

$pdo->exec("INSERT INTO orders (status, subtotal, discount, shipping, total, customer_email, customer_address) VALUES 
    ('completed', 259.80, 0, 0, 259.80, 'cliente1@example.com', 'Rua Exemplo, 123, São Paulo - SP'),
    ('paid', 519.60, 100, 0, 419.60, 'cliente2@example.com', 'Rua Neves, 20, Espírito Santo - ES'),
    ('pending', 199.90, 0, 20.00, 219.90, 'cliente3@example.com', 'Avenida Teste, 456, Rio de Janeiro - RJ')");

$pdo->exec("INSERT INTO order_products (order_id, product_id, quantity, unit_price) VALUES 
    (1, 1, 3, 59.90),
    (1, 2, 1, 199.90),
    (2, 2, 1, 199.90)
");

echo "Seed data inserted successfully.\n";
