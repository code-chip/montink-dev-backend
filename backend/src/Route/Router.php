<?php

/**
 * @copyright  Copyright (c) 2025 Code Chip (https://codechip.com.br)
 * @author     Will <willvix@outlook.com>
 * @Link       https://github.com/code-chip
 */

namespace App\Route;

use App\Controller\ProductController;
use App\Controller\StockController;
use App\Controller\OrderController;
use App\Controller\CouponController;
use App\Controller\WebhookController;

class Router
{
    private string $path = '';
    private array $body = [];

    public function handle(string $uri, string $method): void
    {
        header('Content-Type: application/json');

        $parsedUrl = parse_url($uri);
        $this->path = rtrim($parsedUrl['path'], '/');
        $this->body = json_decode(file_get_contents('php://input'), true) ?? [];

        $method === 'PATCH' ? $this->getPatchId(): null;
        
        switch ("$method $this->path") {
            case 'GET /products':
                (new ProductController())->index();
                break;
            case 'POST /products':
                (new ProductController())->store($this->body);
                break;
            case 'PATCH /products':
                (new ProductController())->update($this->body);
                break;
            case 'GET /stocks':
                (new StockController())->index();
                break;
            case 'POST /stocks':
                (new StockController())->store($this->body);
                break;
            case 'PUT /stocks':
                (new StockController())->update($this->body);
                break;
            case 'GET /orders':
                (new OrderController())->index();
                break;    
            case 'POST /orders':
                (new OrderController())->store($this->body);
                break;
            case 'POST /coupons':
                (new CouponController())->store($this->body);
                break;
            case 'GET /coupons':
                (new CouponController())->index();
                break;
            case 'POST /webhook':
                (new WebhookController())->handle($this->body);
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

    private function getPatchId()
    {
        if (preg_match('#^(/(?:products|stocks|orders|coupons))/(\d+)$#', $this->path, $matches)) {
                $this->path = $matches[1];
                $this->body['id'] = (int) $matches[2];
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid PATCH URL format']);
            return;
        }
    }
}
