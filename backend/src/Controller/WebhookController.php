<?php

namespace App\Controller;

use App\Repository\OrderRepository;

class WebhookController
{
    private OrderRepository $repository;

    public function __construct()
    {
        $this->repository = new OrderRepository();
    }

    public function handle(array $data): array
    {
        $id = $data['id'] ?? null;
        $status = $data['status'] ?? null;

        if (!$id || !$status) {
            return ['error' => 'Invalid payload'];
        }

        if ($status === 'cancelled') {
            $this->repository->delete((int)$id);
            return ['message' => 'Order cancelled and deleted'];
        }

        $this->repository->updateStatus((int)$id, $status);
        return ['message' => 'Order status updated'];
    }
}
