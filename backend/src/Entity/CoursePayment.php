<?php

declare(strict_types=1);

namespace App\Entity;

use Carbon\Carbon;
use JsonSerializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use RandomLib\Factory;

class CoursePayment implements JsonSerializable
{
    /**
     * @var UuidInterface
     */
    protected $id;

    /**
     * @var string
     */
    protected $number;

    /**
     * @var string
     */
    protected $transactionId;

    /**
     * @var int
     */
    protected $method;

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

    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->createdAt = Carbon::now();
        $this->number = $this->generateNumber();
    }

    protected function generateNumber(): string
    {
        $generator = (new Factory())->getLowStrengthGenerator();

        return $generator->generateString(10, '0123456789ABCDEFGHIJKLMNPQRSTUVWXYZ');
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    public function getMethod(): int
    {
        return $this->method;
    }

    public function setMethod(int $method): void
    {
        $this->method = $method;
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
            $instance->setId(Uuid::fromString($row['id']));
        }

        if (isset($row['transaction_id'])) {
            $instance->setTransactionId($row['transaction_id']);
        }

        if (isset($row['number'])) {
            $instance->setNumber($row['number']);
        }

        if (isset($row['method'])) {
            $instance->setMethod($row['method']);
        }

        if (isset($row['metadata'])) {
            $instance->setMetadata((array) json_decode($row['metadata'], true));
        }

        if (isset($row['customer'])) {
            $instance->setCustomer(Uuid::fromString($row['customer']));
        }

        if (isset($row['created_at'])) {
            $instance->setCreatedAt(new Carbon($row['created_at']));
        }

        return $instance;
    }

    public static function fromJson(array $row): CoursePayment
    {
        $instance = new CoursePayment();

        if (isset($row['id'])) {
            $instance->setId(Uuid::fromString($row['id']));
        }

        if (isset($row['transactionId'])) {
            $instance->setTransactionId($row['transactionId']);
        }

        if (isset($row['number'])) {
            $instance->setNumber($row['number']);
        }

        if (isset($row['method'])) {
            $instance->setMethod($row['method']);
        }

        if (isset($row['metadata'])) {
            $instance->setMetadata($row['metadata']);
        }

        if (isset($row['customer'])) {
            $instance->setCustomer(Uuid::fromString($row['customer']));
        }

        if (isset($row['createdAt'])) {
            $instance->setCreatedAt(new Carbon($row['createdAt']));
        }

        return $instance;
    }

    public function toDatabase(): array
    {
        return [
            'id' => $this->id,
            'transaction_id' => $this->transactionId,
            'metadata' => json_encode($this->metadata),
            'number' => $this->number,
            'method' => $this->method,
            'customer' => $this->customer,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'transactionId' => $this->transactionId,
            'number' => $this->number,
            'method' => $this->method,
            'metadata' => $this->metadata,
            'customer' => $this->customer,
            'createdAt' => $this->createdAt->format('Y-m-d\TH:i:s'),
        ];
    }
}
