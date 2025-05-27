<?php

namespace App\Service;

use App\Repository\CouponRepository;

class CouponService
{
    private CouponRepository $repo;

    public function __construct()
    {
        $this->repo = new CouponRepository();
    }

    public function getAll(): array
    {
        return $this->repo->getAll();
    }

    public function create(array $data): array
    {
        $id = $this->repo->create($data);
        return ['coupon_id' => $id];
    }
}
