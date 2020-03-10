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

    public function getAllSKUs(string $skey) {
        try {
            Stripe::setApiKey($skey);

            return \Stripe\SKU::all()->data;
        } catch (Exception $ex) {
            return null;
        }
    }

    public function findSKUForCourse(string $courseId, string $skey) {
        $skus = $this->getAllSKUs($skey);

        foreach($skus as $sku)
        {
            if($sku['attributes']['course_id'] == $courseId) {
                return $sku;
            }
        }

        return null;
    }

    public function addSKUForCourse(array $course, $skey) {
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

    public function placeOrder(Customer $customer, array $cart, int $paymentMethodId, string $skey) {
        try {
            Stripe::setApiKey($skey);

            $items = [];

            foreach($cart as $item) {
                $sku = $this->findSKUForCourse($item['id'], $skey);
                if($sku == null) {
                    $sku = $this->addSKUForCourse($item, $skey);
                }

                $items[] = [
                    'amount' => $item['price'] * $item['quantity'],
                    'currency' => 'usd',
                    'description' => $item['title'],
                    'type' => 'sku',
                    'parent' => $sku['id']
                ];
            }

            $customerToken = $this->db->find("select token from customer_payment_method where deleted_at is null and id = ?", [$paymentMethodId])['token'];

            $stripeCustomer = \Stripe\Customer::retrieve($customerToken);
            $shipping = $stripeCustomer['shipping'];
            
            $order = \Stripe\Order::create([
                'currency' => 'usd',
                'email' => $customer->getLogin(),
                'items' => $items,
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


            $order->pay(['customer' => $stripeCustomer['id']]);

            return true;
        } catch (Exception $e) {
            return null;
        }
    }

    public function addPaymentInfo(Customer $customer, string $token, string $skey, array $billing, array $attendee): bool {
        try {
            Stripe::setApiKey($skey);

            $stripe = \Stripe\Customer::create([
                'source' => $token,
                'email' => $customer->getLogin(),
                'phone' => $attendee['phone'],
                'shipping' => [
                    'name' => $attendee['firstName'].' '.$attendee['lastName'],
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

            $customerPaymentMethod = CustomerPaymentMethod::fromJson([
                'customerId' => $customer->getId(),
                'token' => $stripe->id
            ]);

            $this->db->insert(
                'customer_payment_method',
                $customerPaymentMethod->toDatabase(),
            );

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getPaymentInfo(Customer $customer, string $skey): array {
        try {
            Stripe::setApiKey($skey);

            $paymentMethods = $this->db->findAll('select * from customer_payment_method where deleted_at is null and customer_id = ?', [$customer->getId()]);

            if($paymentMethods == null) {
                return [];
            }

            $result = [];

            foreach ($paymentMethods as $method) {
                $method = CustomerPaymentMethod::fromDatabase($method);

                $stripeCustomer = \Stripe\Customer::retrieve($method->getToken());

                $result[] = [
                    'id' => $method->getId(),
                    'userName' => $customer->getName(),
                    'last4' => $stripeCustomer->sources->data[0]->last4,
                    'expMonth' => $stripeCustomer->sources->data[0]->exp_month,
                    'expYear' => $stripeCustomer->sources->data[0]->exp_year,
                    'brand' => $stripeCustomer->sources->data[0]->brand
                ];
            }

            return $result;
        } catch (Exception $e) {
            return null;
        }
    }

    public function removePaymentInfo(Customer $customer, string $methodId, string $skey): bool {
        try {
            Stripe::setApiKey($skey);

            $method = $this->db->find('select * from customer_payment_method where id = ?', [$methodId]);

            if($method == null) {
                return false;
            }

            $method = CustomerPaymentMethod::fromDatabase($method);

            $stripeCustomer = \Stripe\Customer::retrieve($method->getToken());
            $stripeCustomer->delete();

            $method->setDeletedAt(Carbon::now());

            $this->db->update('customer_payment_method', $method->toDatabase());

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
