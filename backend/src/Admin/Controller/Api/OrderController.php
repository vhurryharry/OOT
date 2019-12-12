<?php

declare(strict_types=1);

namespace App\Admin\Controller\Api;

use App\Admin\Repository\State;
use App\Database;
use App\Entity\CourseReservation;
use App\Entity\Payment;
use Stripe\Charge;
use Stripe\Refund;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @var Database
     */
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * @Route("/search", methods={"POST"})
     */
    public function search(Request $request)
    {
        $orderQuery = <<<SQL
select
    cr.*,
    row_to_json(c) as customer_json,
    row_to_json(co) as course_json,
    row_to_json(cp) as payment_json
from course_reservation as cr
join customer as c on c.id = cr.customer_id
join course as co on co.id = cr.course_id
left join course_payment as cp on cp.id = cr.payment
SQL;
        $state = State::fromDatagrid($request->request->all());
        $orders = $this->db->findAll(
            $orderQuery . $state->toQuery(),
            $state->toQueryParams()
        );

        $items = [];
        foreach ($orders as $order) {
            $items[] = (CourseReservation::fromDatabase($order))->jsonSerialize();
        }

        return new JsonResponse([
            'items' => $items,
            'total' => $this->db->count('course_reservation'),
        ]);
    }

    /**
     * @Route("/find", methods={"POST"})
     */
    public function find(Request $request)
    {
        $order = $this->db->find('select * from course_reservation where id = ?', [$request->get('id')]);
        if (!$order) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($order);
    }

    /**
     * @Route("/update", methods={"POST"})
     */
    public function update(Request $request)
    {
        $order = $this->db->find('select customer_id from course_reservation where id = ?', [$request->get('id')]);

        if (!$order) {
            throw new NotFoundHttpException();
        }

        $this->db->update('course_reservation', [
            'id' => $request->get('id'),
            'status' => $request->get('status'),
        ]);

        if ($request->get('payment')) {
            $payment = new Payment($request->get('payment'));
            $this->db->execute(
                'insert into course_payment (id, transaction_id, customer) values (?, ?, ?)',
                [
                    $payment->getId()->toString(),
                    $payment->getTransactionId(),
                    $order['customer_id'],
                ]
            );
        }

        return new JsonResponse();
    }

    /**
     * @Route("/{paymentId}/payment", methods={"POST"})
     */
    public function payment(Request $request, string $paymentId)
    {
        Stripe::setApiKey(getenv('STRIPE_SKEY'));
        $charge = Charge::retrieve($paymentId);

        return new JsonResponse($charge);
    }

    /**
     * @Route("/{paymentId}/refund", methods={"POST"})
     */
    public function refund(Request $request, string $paymentId)
    {
        Stripe::setApiKey(getenv('STRIPE_SKEY'));
        $refund = Refund::create(['charge' => $paymentId]);

        return new JsonResponse($refund);
    }
}
