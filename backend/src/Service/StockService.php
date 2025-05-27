<?php

namespace App\Service;

use App\Repository\StockRepository;

class StockService
{
    private StockRepository $repo;

    public function __construct()
    {
        $this->repo = new StockRepository();
    }

    public function getAll(): array
    {
        return $this->repo->getAll();
    }

    public function create(array $data): array
    {
        $id = $this->repo->create($data);
        return ['stock_id' => $id];
    }

    public function update(array $data): array
    {
        $updated = $this->repo->update($data);
        return ['updated' => $updated];
    }
}
