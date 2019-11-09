<?php

declare(strict_types=1);

namespace App\Entity;

use Carbon\Carbon;
use JsonSerializable;

class Tag implements JsonSerializable
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Carbon
     */
    protected $createdAt;

    /**
     * @var ?Carbon
     */
    protected $deletedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function setCreatedAt(Carbon $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getDeletedAt(): ?Carbon
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(Carbon $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    public static function fromDatabase(array $row): Tag
    {
        $instance = new Tag();

        if (isset($row['id'])) {
            $instance->setId($row['id']);
        }

        if (isset($row['name'])) {
            $instance->setName($row['name']);
        }

        if (isset($row['created_at'])) {
            $instance->setCreatedAt(new Carbon($row['created_at']));
        }

        if (isset($row['deleted_at'])) {
            $instance->setDeletedAt(new Carbon($row['deleted_at']));
        }

        return $instance;
    }

    public function toDatabase(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deletedAt ? $this->deletedAt->format('Y-m-d H:i:s') : null,
        ];
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'createdAt' => $this->createdAt->format('Y-m-d\TH:i:s'),
            'deletedAt' => $this->deletedAt ? $this->deletedAt->format('Y-m-d\TH:i:s') : null,
        ];
    }
}
