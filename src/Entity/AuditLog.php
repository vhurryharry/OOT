<?php

declare(strict_types=1);

namespace App\Entity;

use Carbon\Carbon;
use JsonSerializable;

class AuditLog implements JsonSerializable
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var int
     */
    protected $tableId;

    /**
     * @var ?int
     */
    protected $user;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var ?array
     */
    protected $old;

    /**
     * @var ?array
     */
    protected $new;

    /**
     * @var Carbon
     */
    protected $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function setTableName(string $tableName): void
    {
        $this->tableName = $tableName;
    }

    public function getTableId(): int
    {
        return $this->tableId;
    }

    public function setTableId(int $tableId): void
    {
        $this->tableId = $tableId;
    }

    public function getUser(): ?int
    {
        return $this->user;
    }

    public function setUser(int $user): void
    {
        $this->user = $user;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    public function getOld(): ?array
    {
        return $this->old;
    }

    public function setOld(array $old): void
    {
        $this->old = $old;
    }

    public function getNew(): ?array
    {
        return $this->new;
    }

    public function setNew(array $new): void
    {
        $this->new = $new;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function setCreatedAt(Carbon $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public static function fromDatabase(array $row): AuditLog
    {
        $instance = new AuditLog();

        if (isset($row['id'])) {
            $instance->setId($row['id']);
        }

        if (isset($row['table_name'])) {
            $instance->setTableName($row['table_name']);
        }

        if (isset($row['table_id'])) {
            $instance->setTableId($row['table_id']);
        }

        if (isset($row['user'])) {
            $instance->setUser($row['user']);
        }

        if (isset($row['action'])) {
            $instance->setAction($row['action']);
        }

        if (isset($row['old'])) {
            $instance->setOld(json_decode($row['old'], true));
        }

        if (isset($row['new'])) {
            $instance->setNew(json_decode($row['new'], true));
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
            'table_name' => $this->tableName,
            'table_id' => $this->tableId,
            'user' => $this->user,
            'action' => $this->action,
            'old' => json_encode($this->old),
            'new' => json_encode($this->new),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'tableName' => $this->tableName,
            'tableId' => $this->tableId,
            'user' => $this->user,
            'action' => $this->action,
            'old' => $this->old,
            'new' => $this->new,
            'createdAt' => $this->createdAt->format('Y-m-d\TH:i:s'),
        ];
    }
}
