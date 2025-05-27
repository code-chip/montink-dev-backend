<?php

namespace App\Controller;

use App\Repository\StockRepository;

class StockController
{
    private StockRepository $repository;

    public function __construct()
    {
        $this->repository = new StockRepository();
    }

    public function index(): array
    {
        return $this->repository->getAll();
    }

    public function store(array $data): array
    {
        $id = $this->repository->create($data);
        return ['id' => $id];
    }

    public function update(array $data): array
    {
        $success = $this->repository->update($data);
        return ['success' => $success];
    }
}
