<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database;
use App\Security\Customer;
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

    public function findByLogin(string $login): array
    {
        return $this->db->find(
            'select * from customer where login = ? and deleted_at is null',
            [$login]
        );
    }

    public function register(array $form): void
    {
        $this->db->execute(
            'insert into customer (id, login, password, confirmation_token, accepts_marketing) values (?, ?, ?, ?, ?)',
            [
                Uuid::uuid4()->toString(),
                $form['email'],
                $this->encoder->encodePassword(new Customer(), $form['password']),
                $this->getRandomKey(),
                0,
            ]
        );
    }

    public function createGuest(string $email): Customer
    {
        $customer = new Customer();
        $customer->setId(Uuid::uuid4());
        $customer->setPassword($this->getRandomKey());

        $this->db->execute(
            "insert into customer (id, login, password, password_expires_at, status) values (?, ?, ?, ?, 'active')",
            [
                $customer->getId()->toString(),
                $email,
                $this->encoder->encodePassword($customer, $customer->getPassword()),
                (new DateTime('now'))->format('Y-m-d H:i:sO'),
            ]
        );

        return $customer;
    }

    protected function getRandomKey(int $size = 32): string
    {
        $generator = (new Factory())->getLowStrengthGenerator();

        return $generator->generateString($size);
    }
}
