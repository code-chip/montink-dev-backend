<?php

namespace App\Service;

use App\Repository\ProductRepository;
use App\Repository\StockRepository;

class ProductService
{
    private ProductRepository $productRepo;
    private StockRepository $stockRepo;

    public function __construct()
    {
        $this->productRepo = new ProductRepository();
        $this->stockRepo = new StockRepository();
    }

    public function getAll(): array
    {
        return $this->productRepo->getAll();
    }

    public function create(array $data): array
    {
        $productId = $this->productRepo->create($data);

        if (isset($data['variations']) && is_array($data['variations'])) {
            foreach ($data['variations'] as $variation) {
                $this->stockRepo->create([
                    'product_id' => $productId,
                    'variation' => $variation['variation'],
                    'quantity' => $variation['quantity'],
                ]);
            }
        }

        return ['product_id' => $productId];
    }

    public function update(array $data): array
    {
        $updated = $this->productRepo->update($data);
        return ['updated' => $updated];
    }
}
