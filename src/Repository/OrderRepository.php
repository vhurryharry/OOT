<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database;
use App\Entity\Order;

class OrderRepository
{
    /**
     * @var Database
     */
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function save(Order $order): Order
    {
        $this->db->execute(
            'insert into course_payment (id, transaction_id, customer) values (?, ?, ?)',
            [
                $order->getPayment()->getId()->toString(),
                $order->getPayment()->getTransactionId(),
                $order->getCustomerId(),
            ]
        );

        foreach ($order->getItems() as $item) {
            $this->db->execute(
                'insert into course_reservation (number, course_id, customer_id, status, payment, option_title, option_price, option_location, option_dates) values (?, ?, ?, ?, ?, ?, ?, ?, ?)',
                [
                    $order->getNumber(),
                    $item['course_id'],
                    $order->getCustomerId(),
                    $order->getStatus(),
                    $order->getPayment()->getId()->toString(),
                    $item['title'],
                    $item['price'],
                    $item['location'],
                    $item['dates'],
                ]
            );
        }
    }
}
