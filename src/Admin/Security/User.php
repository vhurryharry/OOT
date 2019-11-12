<?php

declare(strict_types=1);

namespace App\Admin\Security;

use Carbon\Carbon;
use JsonSerializable;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, JsonSerializable
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var ?array
     */
    protected $metadata;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var ?string
     */
    protected $confirmationToken;

    /**
     * @var Carbon
     */
    protected $createdAt;

    /**
     * @var Carbon
     */
    protected $updatedAt;

    /**
     * @var ?Carbon
     */
    protected $deletedAt;

    /**
     * @var ?Carbon
     */
    protected $expiresAt;

    /**
     * @var ?Carbon
     */
    protected $passwordExpiresAt;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var ?string
     */
    protected $firstName;

    /**
     * @var ?string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var ?string
     */
    protected $rawPassword;

    /**
     * @var ?string
     */
    protected $mfa;

    /**
     * @var array
     */
    protected $permissions = [];

    /**
     * @var array
     */
    protected $roles = [];

    public function __construct()
    {
        $this->createdAt = Carbon::now();
        $this->updatedAt = $this->createdAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function setMetadata(array $metadata): void
    {
        $this->metadata = $metadata;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken(string $confirmationToken): void
    {
        $this->confirmationToken = $confirmationToken;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function setCreatedAt(Carbon $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): Carbon
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(Carbon $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getDeletedAt(): ?Carbon
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(Carbon $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    public function getExpiresAt(): ?Carbon
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(Carbon $expiresAt): void
    {
        $this->expiresAt = $expiresAt;
    }

    public function getPasswordExpiresAt(): ?Carbon
    {
        return $this->passwordExpiresAt;
    }

    public function setPasswordExpiresAt(Carbon $passwordExpiresAt): void
    {
        $this->passwordExpiresAt = $passwordExpiresAt;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getUsername(): string
    {
        return (string) $this->email;
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRawPassword(): ?string
    {
        return $this->rawPassword;
    }

    public function setRawPassword(string $rawPassword): void
    {
        $this->rawPassword = $rawPassword;
    }

    public function getMfa(): ?string
    {
        return $this->mfa;
    }

    public function setMfa(string $mfa): void
    {
        $this->mfa = $mfa;
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function setPermissions(array $permissions): void
    {
        $this->permissions = $permissions;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        $this->rawPassword = null;
    }

    public static function fromDatabase(array $row): User
    {
        $instance = new User();

        if (isset($row['id'])) {
            $instance->setId($row['id']);
        }

        if (isset($row['metadata'])) {
            $instance->setMetadata(json_decode($row['metadata'], true));
        }

        if (isset($row['status'])) {
            $instance->setStatus($row['status']);
        }

        if (isset($row['confirmation_token'])) {
            $instance->setConfirmationToken($row['confirmation_token']);
        }

        if (isset($row['created_at'])) {
            $instance->setCreatedAt(new Carbon($row['created_at']));
        }

        if (isset($row['updated_at'])) {
            $instance->setUpdatedAt(new Carbon($row['updated_at']));
        }

        if (isset($row['deleted_at'])) {
            $instance->setDeletedAt(new Carbon($row['deleted_at']));
        }

        if (isset($row['expires_at'])) {
            $instance->setExpiresAt(new Carbon($row['expires_at']));
        }

        if (isset($row['password_expires_at'])) {
            $instance->setPasswordExpiresAt(new Carbon($row['password_expires_at']));
        }

        if (isset($row['name'])) {
            $instance->setName($row['name']);
        }

        if (isset($row['email'])) {
            $instance->setEmail($row['email']);
        }

        if (isset($row['first_name'])) {
            $instance->setFirstName($row['first_name']);
        }

        if (isset($row['last_name'])) {
            $instance->setLastName($row['last_name']);
        }

        if (isset($row['password'])) {
            $instance->setPassword($row['password']);
        }

        if (isset($row['mfa'])) {
            $instance->setMfa($row['mfa']);
        }

        if (isset($row['permissions'])) {
            $instance->setPermissions(json_decode($row['permissions'], true));
        }

        return $instance;
    }

    public function toDatabase(): array
    {
        return [
            'id' => $this->id,
            'metadata' => json_encode($this->metadata),
            'status' => $this->status,
            'confirmation_token' => $this->confirmationToken,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deletedAt ? $this->deletedAt->format('Y-m-d H:i:s') : null,
            'expires_at' => $this->expiresAt ? $this->expiresAt->format('Y-m-d H:i:s') : null,
            'password_expires_at' => $this->passwordExpiresAt ? $this->passwordExpiresAt->format('Y-m-d H:i:s') : null,
            'name' => $this->name,
            'email' => $this->email,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'password' => $this->password,
            'mfa' => $this->mfa,
            'permissions' => json_encode($this->permissions),
        ];
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'metadata' => $this->metadata,
            'status' => $this->status,
            'confirmationToken' => $this->confirmationToken,
            'createdAt' => $this->createdAt->format('Y-m-d\TH:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d\TH:i:s'),
            'deletedAt' => $this->deletedAt ? $this->deletedAt->format('Y-m-d\TH:i:s') : null,
            'expiresAt' => $this->expiresAt ? $this->expiresAt->format('Y-m-d\TH:i:s') : null,
            'passwordExpiresAt' => $this->passwordExpiresAt ? $this->passwordExpiresAt->format('Y-m-d\TH:i:s') : null,
            'name' => $this->name,
            'email' => $this->email,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'password' => $this->password,
            'mfa' => $this->mfa,
            'permissions' => $this->permissions,
        ];
    }
}
