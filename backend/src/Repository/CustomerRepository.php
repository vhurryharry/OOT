<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database;
use App\Security\Customer;
use App\Entity\CustomerPaymentMethod;
use App\Entity\CoursePayment;
use App\Entity\CourseReservation;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use RandomLib\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

    public function resetPassword(Customer $customer, string $newPassword)
    {
        $customer->setPassword($this->encoder->encodePassword($customer, $newPassword));

        $this->updateUser($customer);
    }

    public function confirmUser(Customer $customer): Customer
    {
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
        if (!$this->encoder->isPasswordValid($customer, $password)) {
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

    public function updateUser(Customer $customer): Customer
    {
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
        $courseReservations = $this->db->findAll('select * from course_reservation where customer_id = ?', [$id]);
    }

    public function getPayments(Customer $customer)
    {
        return $this->db->findAll("select * from course_payment where customer = ?", [$customer->getId()]);
    }

    public function savePayment(Customer $customer, string $transactionId, int $paymentMethodId): CoursePayment
    {
        $coursePayment = new CoursePayment();
        $coursePayment->setCustomer($customer->getId());
        $coursePayment->setMethod($paymentMethodId);
        $coursePayment->setTransactionId($transactionId);

        $this->db->insert(
            'course_payment',
            $coursePayment->toDatabase(),
        );

        return $coursePayment;
    }

    public function reserveCourse(Customer $customer, array $courses, $paymentId)
    {
        foreach ($courses as $course) {
            $courseReservation = new CourseReservation();
            $courseReservation->setPayment($paymentId);
            $courseReservation->setCustomerId($customer->getId());
            $courseReservation->setCourseId(Uuid::fromString($course['id']));
            $courseReservation->setOptionPrice($course['price']);
            $courseReservation->setOptionTitle($course['title']);
            $courseReservation->setStatus('paid');

            $this->db->insert(
                'course_reservation',
                $courseReservation->toDatabase(),
            );
        }
    }

    public function getInstructors()
    {
        $instructors = $this->db->findAll("select id, concat(first_name, ' ', last_name) as name from customer where deleted_at is null and type = 'instructor'");

        return $instructors;
    }
}
