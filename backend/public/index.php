<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/cors.php';

use Dotenv\Dotenv;
use App\Route\Router;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$router = new Router();
$router->handle($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
