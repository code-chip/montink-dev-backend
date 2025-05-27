<?php

namespace App\Controller;

use App\Service\CouponService;

class CouponController
{
    private CouponService $service;

    public function __construct()
    {
        $this->service = new CouponService();
    }

    public function index(): void
    {
        $coupons = $this->service->getAll();
        echo json_encode($coupons);
    }

    public function store(array $data): void
    {
        $result = $this->service->create($data);
        echo json_encode($result);
    }
}
