<?php

declare(strict_types=1);

namespace App\Entity;

use Carbon\Carbon;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;

class Pageview implements JsonSerializable
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var ?string
     */
    protected $tableName;

    /**
     * @var ?int
     */
    protected $tableId;

    /**
     * @var Carbon
     */
    protected $createdAt;

    /**
     * @var ?UuidInterface
     */
    protected $customer;

    /**
     * @var ?string
     */
    protected $city;

    /**
     * @var ?string
     */
    protected $country;

    /**
     * @var ?string
     */
    protected $latlng;

    /**
     * @var ?string
     */
    protected $userAgent;

    /**
     * @var ?string
     */
    protected $ip;

    /**
     * @var ?string
     */
    protected $url;

    /**
     * @var ?string
     */
    protected $title;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTableName(): ?string
    {
        return $this->tableName;
    }

    public function setTableName(string $tableName): void
    {
        $this->tableName = $tableName;
    }

    public function getTableId(): ?int
    {
        return $this->tableId;
    }

    public function setTableId(int $tableId): void
    {
        $this->tableId = $tableId;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function setCreatedAt(Carbon $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getCustomer(): ?UuidInterface
    {
        return $this->customer;
    }

    public function setCustomer(UuidInterface $customer): void
    {
        $this->customer = $customer;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function getLatlng(): ?string
    {
        return $this->latlng;
    }

    public function setLatlng(string $latlng): void
    {
        $this->latlng = $latlng;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public static function fromDatabase(array $row): Pageview
    {
        $instance = new Pageview();

        if (isset($row['id'])) {
            $instance->setId($row['id']);
        }

        if (isset($row['table_name'])) {
            $instance->setTableName($row['table_name']);
        }

        if (isset($row['table_id'])) {
            $instance->setTableId($row['table_id']);
        }

        if (isset($row['created_at'])) {
            $instance->setCreatedAt(new Carbon($row['created_at']));
        }

        if (isset($row['customer'])) {
            $instance->setCustomer($row['customer']);
        }

        if (isset($row['city'])) {
            $instance->setCity($row['city']);
        }

        if (isset($row['country'])) {
            $instance->setCountry($row['country']);
        }

        if (isset($row['latlng'])) {
            $instance->setLatlng($row['latlng']);
        }

        if (isset($row['user_agent'])) {
            $instance->setUserAgent($row['user_agent']);
        }

        if (isset($row['ip'])) {
            $instance->setIp($row['ip']);
        }

        if (isset($row['url'])) {
            $instance->setUrl($row['url']);
        }

        if (isset($row['title'])) {
            $instance->setTitle($row['title']);
        }

        return $instance;
    }

    public static function fromJson(array $row): Pageview
    {
        $instance = new Pageview();

        if (isset($row['id'])) {
            $instance->setId($row['id']);
        }

        if (isset($row['tableName'])) {
            $instance->setTableName($row['tableName']);
        }

        if (isset($row['tableId'])) {
            $instance->setTableId($row['tableId']);
        }

        if (isset($row['createdAt'])) {
            $instance->setCreatedAt(new Carbon($row['createdAt']));
        }

        if (isset($row['customer'])) {
            $instance->setCustomer($row['customer']);
        }

        if (isset($row['city'])) {
            $instance->setCity($row['city']);
        }

        if (isset($row['country'])) {
            $instance->setCountry($row['country']);
        }

        if (isset($row['latlng'])) {
            $instance->setLatlng($row['latlng']);
        }

        if (isset($row['userAgent'])) {
            $instance->setUserAgent($row['userAgent']);
        }

        if (isset($row['ip'])) {
            $instance->setIp($row['ip']);
        }

        if (isset($row['url'])) {
            $instance->setUrl($row['url']);
        }

        if (isset($row['title'])) {
            $instance->setTitle($row['title']);
        }

        return $instance;
    }

    public function toDatabase(): array
    {
        return [
            'id' => $this->id,
            'table_name' => $this->tableName,
            'table_id' => $this->tableId,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'customer' => $this->customer,
            'city' => $this->city,
            'country' => $this->country,
            'latlng' => $this->latlng,
            'user_agent' => $this->userAgent,
            'ip' => $this->ip,
            'url' => $this->url,
            'title' => $this->title,
        ];
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'tableName' => $this->tableName,
            'tableId' => $this->tableId,
            'createdAt' => $this->createdAt->format('Y-m-d\TH:i:s'),
            'customer' => $this->customer,
            'city' => $this->city,
            'country' => $this->country,
            'latlng' => $this->latlng,
            'userAgent' => $this->userAgent,
            'ip' => $this->ip,
            'url' => $this->url,
            'title' => $this->title,
        ];
    }
}
