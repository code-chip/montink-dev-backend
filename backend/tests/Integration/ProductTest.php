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
        require 'seeders/seed_data.php';

        $response = $this->httpRequest('GET', '/products');

        $this->assertEquals(200, $response['status']);
        $this->assertJson($response['body']);

        $products = json_decode($response['body']);
        $this->assertEquals(2, count($products));
    }

    public function testCreateProduct(): void
    {
        require 'seeders/seed_data.php';

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

}    