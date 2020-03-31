<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database;
use App\Security\Customer;
use App\Entity\Course;
use App\Entity\CustomerPaymentMethod;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use RandomLib\Factory;

use Stripe\Stripe;

class PaymentRepository
{
    /**
     * @var Database
     */
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getAllSKUs(string $skey)
    {
        try {
            Stripe::setApiKey($skey);

            return \Stripe\SKU::all()->data;
        } catch (Exception $ex) {
            return null;
        }
    }

    public function findSKUForCourse(string $courseId, string $skey)
    {
        $skus = $this->getAllSKUs($skey);

        foreach ($skus as $sku) {
            if ($sku['attributes']['course_id'] == $courseId) {
                return $sku;
            }
        }

        return null;
    }

    public function addSKUForCourse(array $course, $skey)
    {
        Stripe::setApiKey($skey);

        $product = \Stripe\Product::create([
            'name' => $course['title'],
            'type' => 'good',
            'description' => $course['title'],
            'attributes' => ['course_id'],
        ]);

        return \Stripe\SKU::create([
            'attributes' => [
                'course_id' => $course['id']
            ],
            'price' => $course['price'],
            'currency' => 'usd',
            'inventory' => [
                'type' => 'finite',
                'quantity' => $course['spots']
            ],
            'product' => $product['id']
        ]);
    }

    public function placeOrder(Customer $customer, array $cart, int $paymentMethodId, string $skey)
    {
        try {
            Stripe::setApiKey($skey);

            $customerPaymentMethod = $this->db->find("select * from customer_payment_method where deleted_at is null and id = ?", [$paymentMethodId]);
            if (!$customerPaymentMethod) {
                return null;
            }

            $customerPaymentMethod = CustomerPaymentMethod::fromDatabase($customerPaymentMethod);

            $course = $cart[0];   // For now only one course at a time

            $stripeCustomer = \Stripe\Customer::retrieve($customerPaymentMethod->getToken());
            $shipping = $stripeCustomer['shipping'];

            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $course['price'] * $course['quantity'] * 100,
                'currency' => 'usd',
                'customer' => $customerPaymentMethod->getToken(),
                'payment_method' => $customerPaymentMethod->getPaymentMethod(),
                'off_session' => true,
                'confirm' => true,
                'description' => $course['title'],
                'shipping' => [
                    'name' => $shipping['name'],
                    'address' => [
                        'line1' => $shipping['address']['line1'],
                        'city' => $shipping['address']['city'],
                        'state' => $shipping['address']['state'],
                        'country' => $shipping['address']['country'],
                        'postal_code' => $shipping['address']['postal_code']
                    ]
                ]
            ]);

            return [
                'success' => true,
                'paymentIntent' => $paymentIntent
            ];

            // $items = [];

            // foreach ($cart as $item) {
            //     $sku = $this->findSKUForCourse($item['id'], $skey);
            //     if ($sku == null) {
            //         $sku = $this->addSKUForCourse($item, $skey);
            //     }

            //     $items[] = [
            //         'amount' => $item['price'] * $item['quantity'],
            //         'currency' => 'usd',
            //         'description' => $item['title'],
            //         'type' => 'sku',
            //         'parent' => $sku['id']
            //     ];
            // }

            // $customerToken = $this->db->find("select token from customer_payment_method where deleted_at is null and id = ?", [$paymentMethodId])['token'];

            // $stripeCustomer = \Stripe\Customer::retrieve($customerToken);
            // $shipping = $stripeCustomer['shipping'];

            // $order = \Stripe\Order::create([
            //     'currency' => 'usd',
            //     'email' => $customer->getLogin(),
            //     'items' => $items,
            //     'shipping' => [
            //         'name' => $shipping['name'],
            //         'address' => [
            //             'line1' => $shipping['address']['line1'],
            //             'city' => $shipping['address']['city'],
            //             'state' => $shipping['address']['state'],
            //             'country' => $shipping['address']['country'],
            //             'postal_code' => $shipping['address']['postal_code']
            //         ]
            //     ]
            // ]);

            // return $order->pay(['customer' => $stripeCustomer['id']]);
        } catch (\Stripe\Exception\CardException $e) {
            // Error code will be authentication_required if authentication is needed
            //die('Error code is:' . $e->getError()->code);
            $payment_intent_id = $e->getError()->payment_intent->id;
            $payment_intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);

            return [
                'success' => false,
                'paymentIntent' => $payment_intent
            ];
        } catch (Exception $e) {
            return null;
        }
    }

    public function getClientSecret(Customer $customer, string $skey, array $billing, array $attendee): string
    {
        try {
            Stripe::setApiKey($skey);

            $customerStripeId = "";
            $methods = $this->db->findAll("select * from customer_payment_method where customer_id=?", [$customer->getId()]);

            if (!$methods || count($methods) == 0) {
                $stripe = \Stripe\Customer::create([
                    'email' => $customer->getLogin(),
                    'phone' => $attendee['phone'],
                    'shipping' => [
                        'name' => $attendee['firstName'] . ' ' . $attendee['lastName'],
                        'phone' => $attendee['phone'],
                        'address' => [
                            'line1' => $billing['street'],
                            'city' => $billing['city'],
                            'country' => $billing['country'],
                            'postal_code' => $billing['zip'],
                            'state' => $billing['state']
                        ]
                    ]
                ]);

                $customerStripeId = $stripe->id;
            } else {
                $customerStripeId = $methods[0]['token'];
            }

            $setupIntent = \Stripe\SetupIntent::create([
                'customer' => $customerStripeId,
                'usage' => 'on_session',
            ]);

            $customerPaymentMethod = CustomerPaymentMethod::fromJson([
                'customerId' => $customer->getId(),
                'token' => $customerStripeId,
                'paymentMethod' => $setupIntent->client_secret
            ]);

            $this->db->insert(
                'customer_payment_method',
                $customerPaymentMethod->toDatabase(),
            );

            return $setupIntent->client_secret;
        } catch (Exception $e) {
            return "";
        }
    }

    public function addPaymentInfo(Customer $customer, string $clientSecret, string $pmToken, string $skey): bool
    {
        try {
            Stripe::setApiKey($skey);

            $customerPaymentMethod = $this->db->find("select * from customer_payment_method where customer_id=? and payment_method=?", [$customer->getId(), $clientSecret]);

            $customerPaymentMethod = CustomerPaymentMethod::fromDatabase($customerPaymentMethod);
            $customerPaymentMethod->setPaymentMethod($pmToken);

            $this->db->update(
                'customer_payment_method',
                $customerPaymentMethod->toDatabase(),
            );

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getPaymentInfo(Customer $customer, string $skey): array
    {
        try {
            Stripe::setApiKey($skey);

            $paymentMethods = $this->db->findAll('select * from customer_payment_method where deleted_at is null and customer_id = ?', [$customer->getId()]);

            if ($paymentMethods == null) {
                return [];
            }

            $result = [];

            foreach ($paymentMethods as $method) {
                $method = CustomerPaymentMethod::fromDatabase($method);

                $paymentMethod = \Stripe\PaymentMethod::retrieve($method->getPaymentMethod());

                $result[] = [
                    'id' => $method->getId(),
                    'userName' => $customer->getName(),
                    'stripe' => $paymentMethod,
                    'last4' => $paymentMethod->card->last4,
                    'expMonth' => $paymentMethod->card->exp_month,
                    'expYear' => $paymentMethod->card->exp_year,
                    'brand' => $paymentMethod->card->brand
                ];
            }

            return $result;
        } catch (Exception $e) {
            return null;
        }
    }

    public function removePaymentInfo(Customer $customer, string $methodId, string $skey): bool
    {
        try {
            Stripe::setApiKey($skey);

            $method = $this->db->find('select * from customer_payment_method where id = ?', [$methodId]);

            if ($method == null) {
                return false;
            }

            $method = CustomerPaymentMethod::fromDatabase($method);

            $paymentMethod = \Stripe\PaymentMethod::retrieve($method->getPaymentMethod());
            $paymentMethod->detach();

            $method->setDeletedAt(Carbon::now());

            $this->db->update('customer_payment_method', $method->toDatabase());

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getBillings(array $payments, string $skey)
    {
        $billings = [];

        Stripe::setApiKey($skey);

        foreach ($payments as $payment) {

            try {
                $order = \Stripe\PaymentIntent::retrieve($payment['transaction_id']);

                $billings[] = [
                    'number' => $payment['number'],
                    'amount' => $order['amount'],
                    'currency' => $order['currency'],
                    'order_id' => $order['id'],
                    'paid' => date("F, Y", $order['created'])
                ];
            } catch (Exception $e) {

                $billings[] = [
                    'number' => $payment['number'],
                    'amount' => '0',
                    'currency' => 'usd',
                    'order_id' => $payment['transaction_id'],
                    'paid' => date("F, Y", $payment['created_at'])
                ];
            }
        }

        return $billings;
    }

    public function getBilling(string $billingNumber, string $skey)
    {
        try {
            $payment = $this->db->find("select transaction_id, created_at, number, method from course_payment where number = ?", [$billingNumber]);

            Stripe::setApiKey($skey);

            $method = $this->db->find("select token from customer_payment_method where id = ?", [$payment['method']]);

            $stripeCustomer = \Stripe\Customer::retrieve($method['token']);

            $paymentIntent = \Stripe\PaymentIntent::retrieve($payment['transaction_id']);
            $paymentMethod = \Stripe\PaymentMethod::retrieve(
                $paymentIntent['payment_method']
            );

            $paymentIntent['invoice'] = $payment;
            $paymentIntent['method'] = $paymentMethod->card->brand;

            return $paymentIntent;
        } catch (Exception $e) {
            return null;
        }
    }
}
