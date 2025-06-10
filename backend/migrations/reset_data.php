<?php

/**
 * @copyright  Copyright (c) 2025 Code Chip (https://codechip.com.br)
 * @author     Will <willvix@outlook.com>
 * @Link       https://github.com/code-chip
 */

require_once __DIR__ . '/../src/Database/Connection.php';

use App\Database\Connection;

$pdo = Connection::getInstance();

$pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

// Limpa e reseta IDs das tabelas
$pdo->exec("TRUNCATE TABLE order_products");
$pdo->exec("TRUNCATE TABLE orders");
$pdo->exec("TRUNCATE TABLE stocks");
$pdo->exec("TRUNCATE TABLE coupons");
$pdo->exec("TRUNCATE TABLE products");

// Reativa as restrições de chave estrangeira
$pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

echo "Database cleaned successfully.\n";
