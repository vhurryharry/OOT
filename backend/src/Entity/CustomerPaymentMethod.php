<?php

declare(strict_types=1);

namespace App\Entity;

use Carbon\Carbon;
use JsonSerializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class CustomerPaymentMethod implements JsonSerializable
{
    /**
     * @var int
     */
    protected $id;

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
     * @var ?UuidInterface
     */
    protected $customerId;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $paymentMethod;

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

    public function getDeletedAt(): Carbon
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(Carbon $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    public function getCustomerId(): ?UuidInterface
    {
        return $this->customerId;
    }

    public function setCustomerId(UuidInterface $customerId): void
    {
        $this->customerId = $customerId;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }


    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(string $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    public static function fromDatabase(array $row): CustomerPaymentMethod
    {
        $instance = new CustomerPaymentMethod();

        if (isset($row['id'])) {
            $instance->setId($row['id']);
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

        if (isset($row['customer_id'])) {
            $instance->setCustomerId(Uuid::fromString($row['customer_id']));
        }

        if (isset($row['token'])) {
            $instance->setToken($row['token']);
        }

        if (isset($row['payment_method'])) {
            $instance->setPaymentMethod($row['payment_method']);
        }

        return $instance;
    }

    public static function fromJson(array $row): CustomerPaymentMethod
    {
        $instance = new CustomerPaymentMethod();

        if (isset($row['id'])) {
            $instance->setId($row['id']);
        }

        if (isset($row['createdAt'])) {
            $instance->setCreatedAt(new Carbon($row['createdAt']));
        }

        if (isset($row['updatedAt'])) {
            $instance->setUpdatedAt(new Carbon($row['updatedAt']));
        }

        if (isset($row['deletedAt'])) {
            $instance->setDeletedAt(new Carbon($row['deletedAt']));
        }

        if (isset($row['customerId'])) {
            $instance->setCustomerId(Uuid::fromString($row['customerId']));
        }

        if (isset($row['token'])) {
            $instance->setToken($row['token']);
        }

        if (isset($row['paymentMethod'])) {
            $instance->setPaymentMethod($row['paymentMethod']);
        }

        return $instance;
    }

    public function toDatabase(): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deletedAt ? $this->deletedAt->format('Y-m-d H:i:s') : null,
            'customer_id' => $this->customerId,
            'token' => $this->token,
            'payment_method' => $this->paymentMethod,
        ];
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'createdAt' => $this->createdAt->format('Y-m-d\TH:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d\TH:i:s'),
            'deletedAt' => $this->deletedAt ? $this->deletedAt->format('Y-m-d\TH:i:s') : null,
            'customerId' => $this->customerId,
            'token' => $this->token,
            'paymentMethod' => $this->paymentMethod,
        ];
    }
}
