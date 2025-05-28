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

        if ($itemsArray === null) {
            die("Erro ao decodificar 'products'");
        }

        $prices = array_column($itemsArray, 'price');
        $subtotal = array_sum($prices);

        $shipping = 20;
        if ($subtotal >= 52 && $subtotal <= 166.59) {
            $shipping = 15;
        } elseif ($subtotal > 200) {
            $shipping = 0;
        }

        if (!empty($data['coupon'])) {
            $couponRepo = new CouponRepository();
            $coupon = $couponRepo->getByCode($data['coupon']);
            if ($coupon && $coupon['min_value'] <= $subtotal && strtotime($coupon['expires_at']) > time()) {
                $subtotal -= $coupon['discount'];
            }
        }

        $total = $subtotal + $shipping;

        // Endereço via CEP
        //$cepData = ViaCepHelper::fetch($data['cep']);

        $orderId = $this->repo->create([
            'status' => 'pending',
            'total' => $total,
            'shipping' => $data['shipping'],
            'address' => $data['address'],
            'products' => $itemsArray,
            //'products' => json_encode($data['products']),
        ]);

        //Mailer::send('client@example.com', 'Order Confirmation', "Your order ID is #$orderId");

        return ['order_id' => $orderId, 'total' => $total];
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
