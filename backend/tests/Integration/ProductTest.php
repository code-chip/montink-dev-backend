<?php

/**
 * @copyright  Copyright (c) 2025 Code Chip (https://codechip.com.br)
 * @author     Will <willvix@outlook.com>
 * @Link       https://github.com/code-chip
 */

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use Tests\Traits\HttpRequestTrait;

class ProductTest extends TestCase
{
    use HttpRequestTrait;

    public function testGetCollectionProduct(): void
    {
        $this->refreshDatabase();

        $response = $this->httpRequest('GET', '/products');

        $this->assertEquals(200, $response['status']);
        $this->assertJson($response['body']);

        $products = json_decode($response['body']);
        $this->assertEquals(2, count($products));
    }

    public function testProductCreate(): void
    {
        $this->refreshDatabase();

        $body = [
            'name' => 'Test Integration',
            'price' => 300.00
        ];

        $response = $this->httpRequest('POST', '/products', $body);

        $this->assertEquals(200, $response['status']);
        $this->assertJson($response['body']);
        $product = json_decode($response['body']);
        $this->assertEquals(3, $product->product_id);
    }

    public function testProductUpdate(): void
    {
        $this->refreshDatabase();
        
        $productBefore = $this->getProductById(0);

        $body = [
            'name' => 'T-Shirt Update',
            'price' => '79.99'
        ];

        $response = $this->httpRequest('PATCH', "/products/$productBefore->id", $body);

        $this->assertEquals(200, $response['status']);
        $this->assertJson($response['body']);
        $this->assertEquals('{"updated":true}', $response['body']);


        $productAfter = $this->getProductById(0);
        $this->assertEquals($productBefore->id, $productAfter->id);
        $this->assertNotEquals($productBefore->name, $productAfter->name);
        $this->assertNotEquals($productBefore->price, $productAfter->price);
        $this->assertEquals('T-Shirt Update', $productAfter->name);
        $this->assertEquals('79.99', $productAfter->price);

    }

    public function refreshDatabase(): void
    {
        require 'migrations/reset_data.php';
        require 'seeders/seed_data.php';
    }

    private function getProductById(int $index): Object
    {
        $response = $this->httpRequest('GET', '/products');
        $products = json_decode($response['body']);
        return $products[$index];
    }

}
