<?php

declare(strict_types=1);

namespace App\Security;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class Customer implements UserInterface
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var ?string
     */
    private $login;

    /**
     * @var ?string
     */
    private $password;

    /**
     * @var string[]
     */
    private $roles = [];

    public function __construct(string $login = null, string $password = null, UuidInterface $id = null)
    {
        $this->id = $id ?? Uuid::uuid4();
        $this->login = $login;
        $this->password = $password;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getUsername(): string
    {
        return (string) $this->login;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return $roles;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }
}
