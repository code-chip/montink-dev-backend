<?php

namespace App\Service;

use App\Repository\OrderRepository;
use App\Repository\CouponRepository;
use App\Helper\ViaCepHelper;
use App\Helper\Mailer;

class OrderService
{
    private OrderRepository $repo;

    public function __construct()
    {
        $this->repo = new OrderRepository();
    }

    public function getAll(): array
    {
        return $this->repo->getAll();
    }

    public function create(array $data): array
    {
        $itemsArray = json_decode($data['products'], true);

        // Endereço via CEP
        //$cepData = ViaCepHelper::fetch($data['cep']);

        $orderId = $this->repo->create([
            'status' => $data['status'],
            'total' => $data['total'],
            'shipping' => $data['shipping'],
            'address' => $data['address'],
            'products' => $itemsArray,
        ]);

        //Mailer::send('client@example.com', 'Order Confirmation', "Your order ID is #$orderId");

        return ['order_id' => $orderId, 'total' => $data['total']];
    }

    public function delete(int $id): void
    {
        $this->repo->delete($id);
    }

    public function updateStatus(int $id, string $status): void
    {
        $this->repo->updateStatus($id, $status);
    }
}
