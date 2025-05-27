<?php

namespace App\Controller;

use App\Service\ProductService;

class ProductController
{
    private ProductService $service;

    public function __construct()
    {
        $this->service = new ProductService();
    }

    public function index(): void
    {
        $products = $this->service->getAll();
        echo json_encode($products);
    }

    public function store(array $data): void
    {
        $result = $this->service->create($data);
        echo json_encode($result);
    }

    public function update(array $data): void
    {
        $result = $this->service->update($data);
        echo json_encode($result);
    }
}
