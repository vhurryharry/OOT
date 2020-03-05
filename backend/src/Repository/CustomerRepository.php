<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database;
use App\Security\Customer;
use App\Entity\CustomerPaymentMethod;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use RandomLib\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Stripe\Stripe;

class CustomerRepository
{
    /**
     * @var Database
     */
    protected $db;

    /**
     * @var UserPasswordEncoderInterface
     */
    protected $encoder;

    public function __construct(Database $db, UserPasswordEncoderInterface $encoder)
    {
        $this->db = $db;
        $this->encoder = $encoder;
    }

    public function findReservations(Customer $customer): array
    {
        return $this->db->findAll(
            'select * from course_reservation as cr join course as c on cr.course_id = c.id where cr.customer_id = ?',
            [$customer->getId()->toString()]
        );
    }

    public function findByLogin(string $login): array
    {
        return $this->db->find(
            'select * from customer where login = ? and deleted_at is null',
            [$login]
        );
    }

    public function getCustomer(string $id): array
    {
        return $this->db->find(
            'select * from customer where id = ? and deleted_at is null',
            [$id]
        );
    }

    public function resetPassword(Customer $customer, string $newPassword): Customer {
        $customer->setPassword($this->encoder->encodePassword($customer, $newPassword));

        $this->updateUser($customer);
    }

    public function confirmUser(Customer $customer): Customer {
        $customer->setStatus(Customer::ACTIVE);

        $this->updateUser($customer);
        return $customer;
    }

    public function register(array $form): Customer
    {
		$rawPassword = $form['password'];
		$customer = Customer::fromJson($form);
		$customer->setId(Uuid::uuid4());
		$customer->setLogin($form['email']);

        $customer->setPassword($this->encoder->encodePassword($customer, $rawPassword));
        $customer->setConfirmationToken($this->getRandomKey());
		$customer->setStatus(Customer::PENDING_CONFIRMATION);

        $this->db->insert(
            'customer',
            $customer->toDatabase(),
        );

        return $customer;
    }

    public function createGuest(string $email, string $name, string $phone): Customer
    {
        $rawPassword = $this->getRandomKey();
        $customer = new Customer(
            $email,
            Uuid::uuid4()
        );

        $customer->setPassword($this->encoder->encodePassword($customer, $rawPassword));
        $customer->setRawPassword($rawPassword);
        $customer->setPasswordExpiresAt(Carbon::now());
        $customer->setStatus(Customer::PENDING_PASSWORD_RESET);

        $this->db->insert(
            'customer',
            $customer->toDatabase(),
        );

        return $customer;
    }

    public function checkPassword(Customer $customer, string $password): bool 
    {
        if(!$this->encoder->isPasswordValid($customer, $password)) {
			return false;
        }

        return true;
    }

    public function updatePassword(Customer $customer, string $newPassword): Customer 
    {
        $customer->setPassword($this->encoder->encodePassword($customer, $newPassword));

        $this->updateUser($customer);

        return $customer;
    }

    public function updateUser(Customer $customer): Customer {
        $this->db->update('customer', $customer->toDatabase());

        return $customer;
    }

    protected function getRandomKey(int $size = 32): string
    {
        $generator = (new Factory())->getLowStrengthGenerator();

        return $generator->generateString($size);
    }

    public function getMyCourses(string $id) 
    {
        $courseReservations = $this->db->findAll('select  * from course_reservation where customer_id = ?', $id);
    }

    public function addPaymentInfo(Customer $customer, string $token, string $skey): bool {
        try {
            Stripe::setApiKey($skey);

            $stripe = \Stripe\Customer::create([
                'source' => $token,
                'email' => $customer->getLogin(),
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

            $paymentMethods = $this->db->findAll('select * from customer_payment_method where customer_id = ?', [$customer->getId()]);

            if($paymentMethods == null) {
                return [];
            }

            $result = [];

            foreach ($paymentMethods as $method) {
                $method = CustomerPaymentMethod::fromDatabase($method);

                $stripeCustomer = \Stripe\Customer::retrieve($method->getToken());

                $result[] = [
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
}
