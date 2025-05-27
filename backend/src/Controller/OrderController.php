<?php

namespace App\Controller;

use App\Helper\Mailer;
use App\Helper\ViaCepHelper;
use App\Service\OrderService;

class OrderController
{
    private OrderService $repository;

    public function __construct()
    {
        $this->service = new OrderService();
    }

    public function store(array $data): array
    {
        $cepData = ViaCepHelper::fetch($data['cep']);
        $address = "{$cepData['logradouro']}, {$cepData['bairro']}, {$cepData['localidade']} - {$cepData['uf']}";

        $orderData = [
            'total' => $data['total'],
            'status' => 'pending',
            'address' => $address,
            'items' => json_encode($data['items']),
        ];

        $id = $this->service->create($orderData);

        Mailer::send(
            $data['email'],
            'Order Confirmation',
            "Thank you! Your order ID is {$id}. We will ship to: {$address}"
        );

        return ['order_id' => $id];
    }
}
