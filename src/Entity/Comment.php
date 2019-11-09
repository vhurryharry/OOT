<?php

declare(strict_types=1);

namespace App\Entity;

use Carbon\Carbon;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;

class Comment implements JsonSerializable
{
    /**
     * @var UuidInterface
     */
    protected $id;

    /**
     * @var ?UuidInterface
     */
    protected $entity;

    /**
     * @var ?UuidInterface
     */
    protected $author;

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

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
    }

    public function getEntity(): ?UuidInterface
    {
        return $this->entity;
    }

    public function setEntity(UuidInterface $entity): void
    {
        $this->entity = $entity;
    }

    public function getAuthor(): ?UuidInterface
    {
        return $this->author;
    }

    public function setAuthor(UuidInterface $author): void
    {
        $this->author = $author;
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

    public static function fromDatabase(array $row): Comment
    {
        $instance = new Comment();

        if (isset($row['id'])) {
            $instance->setId($row['id']);
        }

        if (isset($row['entity'])) {
            $instance->setEntity($row['entity']);
        }

        if (isset($row['author'])) {
            $instance->setAuthor($row['author']);
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

        return $instance;
    }

    public function toDatabase(): array
    {
        return [
            'id' => $this->id,
            'entity' => $this->entity,
            'author' => $this->author,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deletedAt ? $this->deletedAt->format('Y-m-d H:i:s') : null,
        ];
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'entity' => $this->entity,
            'author' => $this->author,
            'createdAt' => $this->createdAt->format('Y-m-d\TH:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d\TH:i:s'),
            'deletedAt' => $this->deletedAt ? $this->deletedAt->format('Y-m-d\TH:i:s') : null,
        ];
    }
}
