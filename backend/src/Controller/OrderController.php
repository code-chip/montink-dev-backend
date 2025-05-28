<?php

namespace App\Controller;

use App\Helper\Mailer;
use App\Helper\ViaCepHelper;
use App\Service\OrderService;

class OrderController
{
    private OrderService $service;

    public function __construct()
    {
        $this->service = new OrderService();
    }

    public function index(): void
    {
        $orders = $this->service->getAll();
        echo json_encode($orders);
    }

    public function store(array $data): void
    {
        $cepData = ViaCepHelper::fetch($data['cep']);
        $address = "{$cepData['logradouro']}, {$cepData['bairro']}, {$cepData['localidade']} - {$cepData['uf']}";

        $orderData = [
            'status' => 'pending',
            'total' => $data['total'],
            'shipping' => $data['shipping'],
            'address' => $address,
            'products' => json_encode($data['products']),
        ];

        $id = $this->service->create($orderData);

        // Mailer::send(
        //     $data['email'],
        //     'Order Confirmation',
        //     "Thank you! Your order ID is {$id}. We will ship to: {$address}"
        // );

        //return ['order_id' => $id];
        echo json_encode($id);
    }
}
