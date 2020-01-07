<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Output\OrderOutput;
use App\Entity\Order;
use App\Entity\Payment;
use App\Event\CustomerAutoRegistered;
use App\Event\OrderPlaced;
use App\Repository\CartRepository;
use App\Repository\CustomerRepository;
use App\Repository\OrderRepository;
use App\Security\Customer;
use Ramsey\Uuid\Uuid;
use Stripe\Charge;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use App\Repository\State;
use App\Database;
use App\Entity\CourseReservation;
use Stripe\Refund;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends AbstractController
{
    /**
     * @var CartRepository
     */
    protected $cartRepository;

    /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * @var OrderOutput
     */
    protected $output;

    /**
     * @var EventDispatcherInterface
     */
	protected $eventDispatcher;
	
    /**
     * @var Database
     */
    protected $db;

    public function __construct(
        CartRepository $cartRepository,
        OrderRepository $orderRepository,
        CustomerRepository $customerRepository,
        OrderOutput $output,
		EventDispatcherInterface $eventDispatcher,
		Database $db
    ) {
        $this->cartRepository = $cartRepository;
        $this->orderRepository = $orderRepository;
        $this->customerRepository = $customerRepository;
        $this->output = $output;
        $this->eventDispatcher = $eventDispatcher;
        $this->db = $db;
    }

    /**
     * @Route("/order", name="order")
     */
    public function order(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $selectedOption = $request->get('option');
            $this->cartRepository->add((int) $selectedOption);
        }

        return $this->render('order.html.twig');
    }

    /**
     * @Route("/api/order/remove", name="api_remove_course", methods={"POST"})
     */
    public function removeCourse(Request $request)
    {
        $this->cartRepository->remove(Uuid::fromString($request->request->get('id')));
        $cart = $this->cartRepository->get();

        return new JsonResponse($this->output->fromCart($cart));
    }

    /**
     * @Route("/api/order/recalculate", name="api_recalculate", methods={"POST"})
     */
    public function recalculate(Request $request)
    {
        $cart = $this->cartRepository->get();

        return new JsonResponse($this->output->fromCart($cart));
    }

    /**
     * @Route("/api/order/place", name="api_place_order", methods={"POST"})
     */
    public function placeOrder(Request $request)
    {
        $cart = $this->cartRepository->get();
        $customer = $this->getUser();

        if (!($customer instanceof Customer)) {
            $customer = $this->customerRepository->createGuest(
                $request->request->get('email'),
                $request->request->get('name'),
                $request->request->get('phone')
            );

            $this->eventDispatcher->dispatch(new CustomerAutoRegistered($customer));
        }

        $order = new Order($customer);
        $order->setItems($cart->getItems());

        // Payment
        Stripe::setApiKey(getenv('STRIPE_SKEY'));
        $transaction = Charge::create([
            'amount' => $cart->getGrandTotal()->getAmount(),
            'currency' => strtolower($cart->getCurrency()->getCode()),
            'description' => 'Course Order #' . $order->getNumber(),
            'source' => $request->request->get('token'),
            'metadata' => [
                'order_number' => $order->getNumber(),
            ],
        ]);

        if ($transaction->status !== 'succeeded') {
            return new JsonResponse(
                [
                    'message' => $transaction->failure_message ?? 'Unable to process payment',
                ],
                JsonResponse::HTTP_PAYMENT_REQUIRED
            );
        }

        $payment = new Payment($transaction->id);
        $order->setPayment($payment);
        $this->orderRepository->save($order);
        $this->eventDispatcher->dispatch(new OrderPlaced($order));

        return new JsonResponse([
            'number' => $order->getNumber(),
        ]);
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
