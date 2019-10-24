<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CartRepository;
use App\Repository\CustomerRepository;
use App\Repository\OrderRepository;
use App\Security\Customer;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
use NumberFormatter;
use Stripe\Charge;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @var CartRepository
     */
    protected $cartRepository;

    public function __construct(
        CartRepository $cartRepository,
        OrderRepository $orderRepository,
        CustomerRepository $customerRepository
    ) {
        $this->cartRepository = $cartRepository;
        $this->orderRepository = $orderRepository;
        $this->customerRepository = $customerRepository;
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
     * @Route("/api/order/recalculate", name="api_recalculate_order", methods={"POST"})
     */
    public function recalculateOrder(Request $request)
    {
        $cart = $this->cartRepository->get();
        $numberFormatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, new ISOCurrencies());

        return new JsonResponse([
            'items' => $cart->getItems(),
            'grandTotal' => $moneyFormatter->format($cart->getGrandTotal()),
        ]);
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
                $request->get('email'),
                $request->get('name'),
                $request->get('phone')
            );
        }

        $order = new Order($customer->getId()->toString());
        $order->setItems($cart->getItems());

        // Payment
        Stripe::setApiKey(getenv('STRIPE_SKEY'));
        $transaction = Charge::create([
            'amount' => $cart->getGrandTotal()->getAmount(),
            'currency' => strtolower($cart->getCurrency()->getCode()),
            'description' => 'Course Order #' . $order->getNumber(),
            'source' => $request->get('token'),
            'metadata' => [
                'order_number' => $order->getNumber(),
            ],
        ]);

        if ($transaction->status !== 'succeeded') {
            $this->addFlash('danger', $transaction->failure_message ?? 'Unable to process payment.');

            return $this->redirectToRoute('order');
        }

        $payment = new Payment($transaction->id);
        $order->setPayment($payment);
        $this->orderRepository->save($order);

        return $this->render('orderSuccess.html.twig');
    }
}
