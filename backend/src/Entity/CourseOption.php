<?php

declare(strict_types=1);

namespace App\Entity;

use Carbon\Carbon;
use JsonSerializable;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use NumberFormatter;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class CourseOption implements JsonSerializable
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var int
     */
    protected $price;

    /**
     * @var ?array
     */
    protected $dates;

    /**
     * @var bool
     */
    protected $combo = false;

    /**
     * @var ?UuidInterface
     */
    protected $course;

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

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function getDates(): ?array
    {
        return $this->dates;
    }

    public function setDates(array $dates): void
    {
        $this->dates = $dates;
    }

    public function getCombo(): bool
    {
        return $this->combo;
    }

    public function setCombo(bool $combo): void
    {
        $this->combo = $combo;
    }

    public function getCourse(): ?UuidInterface
    {
        return $this->course;
    }

    public function setCourse(UuidInterface $course): void
    {
        $this->course = $course;
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

    public static function fromDatabase(array $row): CourseOption
    {
        $instance = new CourseOption();

        if (isset($row['id'])) {
            $instance->setId($row['id']);
        }

        if (isset($row['title'])) {
            $instance->setTitle($row['title']);
        }

        if (isset($row['price'])) {
            $instance->setPrice($row['price']);
        }

        if (isset($row['dates'])) {
            $rawDates = json_decode($row['dates'], true);
            $dates = [];

            foreach ($rawDates as $rawDate) {
                $dates[] = new Carbon($rawDate);
            }

            $instance->setDates($dates);
        }

        if (isset($row['combo'])) {
            $instance->setCombo($row['combo']);
        }

        if (isset($row['course'])) {
            $instance->setCourse(Uuid::fromString($row['course']));
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

    public static function fromJson(array $row): CourseOption
    {
        $instance = new CourseOption();

        if (isset($row['id'])) {
            $instance->setId($row['id']);
        }

        if (isset($row['title'])) {
            $instance->setTitle($row['title']);
        }

        if (isset($row['price'])) {
            $instance->setPrice($row['price']);
        }

        if (isset($row['dates'])) {
            $instance->setDates($row['dates']);
        }

        if (isset($row['combo'])) {
            $instance->setCombo((bool) $row['combo']);
        }

        if (isset($row['course'])) {
            $instance->setCourse(Uuid::fromString($row['course']));
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
            'title' => $this->title,
            'price' => $this->price,
            'dates' => json_encode($this->dates),
            'combo' => $this->combo,
            'course' => $this->course,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deletedAt ? $this->deletedAt->format('Y-m-d H:i:s') : null,
        ];
    }

    public function jsonSerialize(): array
    {
        $numberFormatter = new NumberFormatter('en', NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, new ISOCurrencies());
        $dates = [];

        foreach ((array) $this->dates as $date) {
            $dates[] = $date->format('m/d/Y');
        }

        $dates = array_values(array_unique($dates));

        return [
            'id' => $this->id,
            'title' => $this->title,
            'curPrice' => $moneyFormatter->format(
                new Money($this->price, new Currency('USD')),
            ),
            'price' => $this->price,
            'dates' => $dates,
            'combo' => $this->combo,
            'course' => $this->course,
            'createdAt' => $this->createdAt->format('Y-m-d\TH:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d\TH:i:s'),
            'deletedAt' => $this->deletedAt ? $this->deletedAt->format('Y-m-d\TH:i:s') : null,
        ];
    }
}
