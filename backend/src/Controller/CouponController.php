<?php

namespace App\Controller;

use App\Repository\CouponRepository;

class CouponController
{
    private CouponRepository $repository;

    public function __construct()
    {
        $this->repository = new CouponRepository();
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
}
