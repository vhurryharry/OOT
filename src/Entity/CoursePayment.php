<?php

declare(strict_types=1);

namespace App\Entity;

use Carbon\Carbon;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;

class CoursePayment implements JsonSerializable
{
    /**
     * @var UuidInterface
     */
    protected $id;

    /**
     * @var string
     */
    protected $transactionId;

    /**
     * @var ?array
     */
    protected $metadata;

    /**
     * @var ?UuidInterface
     */
    protected $customer;

    /**
     * @var Carbon
     */
    protected $createdAt;

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function setTransactionId(string $transactionId): void
    {
        $this->transactionId = $transactionId;
    }

    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function setMetadata(array $metadata): void
    {
        $this->metadata = $metadata;
    }

    public function getCustomer(): ?UuidInterface
    {
        return $this->customer;
    }

    public function setCustomer(UuidInterface $customer): void
    {
        $this->customer = $customer;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function setCreatedAt(Carbon $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public static function fromDatabase(array $row): CoursePayment
    {
        $instance = new CoursePayment();

        if (isset($row['id'])) {
            $instance->setId($row['id']);
        }

        if (isset($row['transaction_id'])) {
            $instance->setTransactionId($row['transaction_id']);
        }

        if (isset($row['metadata'])) {
            $instance->setMetadata(json_decode($row['metadata'], true));
        }

        if (isset($row['customer'])) {
            $instance->setCustomer($row['customer']);
        }

        if (isset($row['created_at'])) {
            $instance->setCreatedAt(new Carbon($row['created_at']));
        }

        return $instance;
    }

    public function toDatabase(): array
    {
        return [
            'id' => $this->id,
            'transaction_id' => $this->transactionId,
            'metadata' => json_encode($this->metadata),
            'customer' => $this->customer,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'transactionId' => $this->transactionId,
            'metadata' => $this->metadata,
            'customer' => $this->customer,
            'createdAt' => $this->createdAt->format('Y-m-d\TH:i:s'),
        ];
    }
}
