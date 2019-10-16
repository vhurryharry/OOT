<?php

declare(strict_types=1);

namespace App\Security;

use App\Repository\CustomerRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class CustomerProvider implements UserProviderInterface
{
    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function loadUserByUsername($username)
    {
        return $this->getCustomer($username);
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof Customer) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        return $this->getCustomer($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === Customer::class;
    }

    protected function getCustomer(string $username)
    {
        $customer = $this->customerRepository->findByLogin($username);

        if (!$customer) {
            throw new UsernameNotFoundException();
        }

        return new Customer($customer['login'], $customer['password']);
    }
}
