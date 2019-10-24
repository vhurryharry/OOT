<?php

declare(strict_types=1);

namespace App\Entity;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Payment
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $transactionId;

    public function __construct(string $transactionId)
    {
        $this->id = Uuid::uuid4();
        $this->transactionId = $transactionId;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }
}
