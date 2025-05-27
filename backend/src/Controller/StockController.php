<?php

namespace App\Controller;

use App\Service\StockService;

class StockController
{
    private StockService $service;

    public function __construct()
    {
        $this->service = new StockService();
    }

    public function index(): void
    {
        $stock = $this->service->getAll();
        echo json_encode($stock);
    }

    public function store(array $data): void
    {
        $stock = $this->service->create($data);
        echo json_encode($stock);
    }

    public function update(array $data): array
    {
        $success = $this->service->update($data);
        return ['success' => $success];
    }
}
