<?php

declare(strict_types=1);

namespace App\Entity;

use Carbon\Carbon;
use JsonSerializable;

class Role implements JsonSerializable
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
     * @var ?int
     */
    protected $parent;

    /**
     * @var ?array
     */
    protected $permissions;

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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getParent(): ?int
    {
        return $this->parent;
    }

    public function setParent(int $parent): void
    {
        $this->parent = $parent;
    }

    public function getPermissions(): ?array
    {
        return $this->permissions;
    }

    public function setPermissions(array $permissions): void
    {
        $this->permissions = $permissions;
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

    public static function fromDatabase(array $row): Role
    {
        $instance = new Role();

        if (isset($row['id'])) {
            $instance->setId($row['id']);
        }

        if (isset($row['name'])) {
            $instance->setName($row['name']);
        }

        if (isset($row['parent'])) {
            $instance->setParent($row['parent']);
        }

        if (isset($row['permissions'])) {
            $instance->setPermissions((array) json_decode($row['permissions'], true));
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

    public static function fromJson(array $row): Role
    {
        $instance = new Role();

        if (isset($row['id'])) {
            $instance->setId($row['id']);
        }

        if (isset($row['name'])) {
            $instance->setName($row['name']);
        }

        if (isset($row['parent'])) {
            $instance->setParent($row['parent']);
        }

        if (isset($row['permissions'])) {
            $instance->setPermissions($row['permissions']);
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

        return $instance;
    }

    public function toDatabase(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'parent' => $this->parent,
            'permissions' => json_encode($this->permissions),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deletedAt ? $this->deletedAt->format('Y-m-d H:i:s') : null,
        ];
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'parent' => $this->parent,
            'permissions' => $this->permissions,
            'createdAt' => $this->createdAt->format('Y-m-d\TH:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d\TH:i:s'),
            'deletedAt' => $this->deletedAt ? $this->deletedAt->format('Y-m-d\TH:i:s') : null,
        ];
    }
}
