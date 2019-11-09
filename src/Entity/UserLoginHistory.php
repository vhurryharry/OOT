<?php

declare(strict_types=1);

namespace App\Entity;

use Carbon\Carbon;
use JsonSerializable;

class UserLoginHistory implements JsonSerializable
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var ?int
     */
    protected $user;

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

    public function getUser(): ?int
    {
        return $this->user;
    }

    public function setUser(int $user): void
    {
        $this->user = $user;
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

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function setCreatedAt(Carbon $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public static function fromDatabase(array $row): UserLoginHistory
    {
        $instance = new UserLoginHistory();

        if (isset($row['id'])) {
            $instance->setId($row['id']);
        }

        if (isset($row['user'])) {
            $instance->setUser($row['user']);
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

        if (isset($row['created_at'])) {
            $instance->setCreatedAt(new Carbon($row['created_at']));
        }

        return $instance;
    }

    public function toDatabase(): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'city' => $this->city,
            'country' => $this->country,
            'latlng' => $this->latlng,
            'user_agent' => $this->userAgent,
            'ip' => $this->ip,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'city' => $this->city,
            'country' => $this->country,
            'latlng' => $this->latlng,
            'userAgent' => $this->userAgent,
            'ip' => $this->ip,
            'createdAt' => $this->createdAt->format('Y-m-d\TH:i:s'),
        ];
    }
}
