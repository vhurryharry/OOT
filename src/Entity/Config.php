<?php

declare(strict_types=1);

namespace App\Entity;

use JsonSerializable;

class Config implements JsonSerializable
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $value;

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public static function fromDatabase(array $row): Config
    {
        $instance = new Config();

        if (isset($row['key'])) {
            $instance->setKey($row['key']);
        }

        if (isset($row['value'])) {
            $instance->setValue($row['value']);
        }

        return $instance;
    }

    public function toDatabase(): array
    {
        return [
            'key' => $this->key,
            'value' => $this->value,
        ];
    }

    public function jsonSerialize(): array
    {
        return [
            'key' => $this->key,
            'value' => $this->value,
        ];
    }
}
