<?php

declare(strict_types=1);

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function loadUserByUsername($username)
    {
        return $this->getUser($username);
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        return $this->getUser($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === User::class;
    }

    protected function getUser(string $username)
    {
        $user = $this->userRepository->findByEmail($username);

        if (!$user) {
            throw new UsernameNotFoundException();
        }

        return User::fromDatabase($user);
    }
}
