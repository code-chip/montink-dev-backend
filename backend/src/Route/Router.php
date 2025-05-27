<?php

namespace App\Route;

use App\Controller\ProductController;
use App\Controller\StockController;
use App\Controller\OrderController;
use App\Controller\CouponController;
use App\Controller\WebhookController;

class Router
{
    public function handle(string $uri, string $method): void
    {
        header('Content-Type: application/json');

        $parsedUrl = parse_url($uri);
        $path = rtrim($parsedUrl['path'], '/');
        $body = json_decode(file_get_contents('php://input'), true) ?? [];

        switch ("$method $path") {
            case 'GET /products':
                (new ProductController())->index();
                break;
            case 'POST /products':
                (new ProductController())->store($body);
                break;
            case 'PUT /products':
                (new ProductController())->update($body);
                break;
            case 'GET /stocks':
                (new StockController())->index();
                break;
            case 'POST /stocks':
                (new StockController())->store($body);
                break;
            case 'PUT /stocks':
                (new StockController())->update($body);
                break;
            case 'GET /orders':
                (new OrderController())->index();
                break;    
            case 'POST /orders':
                (new OrderController())->store($body);
                break;
            case 'POST /coupons':
                (new CouponController())->store($body);
                break;
            case 'GET /coupons':
                (new CouponController())->index();
                break;
            case 'POST /webhook':
                (new WebhookController())->handle($body);
                break;
            case 'GET /phpinfo':
                header('Content-Type: text/html');
                phpinfo();
                break;    
            default:
                http_response_code(404);
                echo json_encode(['error' => 'Route not found']);
        }
    }
}
