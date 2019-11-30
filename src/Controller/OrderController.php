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

    public function __construct(
        CartRepository $cartRepository,
        OrderRepository $orderRepository,
        CustomerRepository $customerRepository,
        OrderOutput $output,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->cartRepository = $cartRepository;
        $this->orderRepository = $orderRepository;
        $this->customerRepository = $customerRepository;
        $this->output = $output;
        $this->eventDispatcher = $eventDispatcher;
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
}
