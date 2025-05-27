<?php
require_once __DIR__ . '/../src/Database/Connection.php';

use App\Database\Connection;

$pdo = (new Connection())->getConnection();

$queries = [
    "CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        price DECIMAL(10,2) NOT NULL
    )",
    "CREATE TABLE IF NOT EXISTS stocks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT,
        variation VARCHAR(255),
        quantity INT,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )",
    "CREATE TABLE IF NOT EXISTS coupons (
        id INT AUTO_INCREMENT PRIMARY KEY,
        code VARCHAR(50) NOT NULL,
        discount DECIMAL(10,2) NOT NULL,
        min_value DECIMAL(10,2),
        expires_at DATETIME
    )",
    "CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        status VARCHAR(50) DEFAULT 'pending',
        total DECIMAL(10,2),
        shipping DECIMAL(10,2),
        customer_email VARCHAR(255),
        customer_address TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )"
];

foreach ($queries as $query) {
    $pdo->exec($query);
}

echo "Tables created successfully.\n";
