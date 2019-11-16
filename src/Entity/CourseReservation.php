<?php

declare(strict_types=1);

namespace App\Entity;

use Carbon\Carbon;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;

class CourseReservation implements JsonSerializable
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var ?array
     */
    protected $optionDates;

    /**
     * @var Carbon
     */
    protected $createdAt;

    /**
     * @var Carbon
     */
    protected $updatedAt;

    /**
     * @var string
     */
    protected $number;

    /**
     * @var ?UuidInterface
     */
    protected $courseId;

    /**
     * @var ?UuidInterface
     */
    protected $customerId;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var ?UuidInterface
     */
    protected $payment;

    /**
     * @var string
     */
    protected $optionTitle;

    /**
     * @var int
     */
    protected $optionPrice;

    /**
     * @var ?string
     */
    protected $optionLocation;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getOptionDates(): ?array
    {
        return $this->optionDates;
    }

    public function setOptionDates(array $optionDates): void
    {
        $this->optionDates = $optionDates;
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

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    public function getCourseId(): ?UuidInterface
    {
        return $this->courseId;
    }

    public function setCourseId(UuidInterface $courseId): void
    {
        $this->courseId = $courseId;
    }

    public function getCustomerId(): ?UuidInterface
    {
        return $this->customerId;
    }

    public function setCustomerId(UuidInterface $customerId): void
    {
        $this->customerId = $customerId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getPayment(): ?UuidInterface
    {
        return $this->payment;
    }

    public function setPayment(UuidInterface $payment): void
    {
        $this->payment = $payment;
    }

    public function getOptionTitle(): string
    {
        return $this->optionTitle;
    }

    public function setOptionTitle(string $optionTitle): void
    {
        $this->optionTitle = $optionTitle;
    }

    public function getOptionPrice(): int
    {
        return $this->optionPrice;
    }

    public function setOptionPrice(int $optionPrice): void
    {
        $this->optionPrice = $optionPrice;
    }

    public function getOptionLocation(): ?string
    {
        return $this->optionLocation;
    }

    public function setOptionLocation(string $optionLocation): void
    {
        $this->optionLocation = $optionLocation;
    }

    public static function fromDatabase(array $row): CourseReservation
    {
        $instance = new CourseReservation();

        if (isset($row['id'])) {
            $instance->setId($row['id']);
        }

        if (isset($row['option_dates'])) {
            $instance->setOptionDates((array) json_decode($row['option_dates'], true));
        }

        if (isset($row['created_at'])) {
            $instance->setCreatedAt(new Carbon($row['created_at']));
        }

        if (isset($row['updated_at'])) {
            $instance->setUpdatedAt(new Carbon($row['updated_at']));
        }

        if (isset($row['number'])) {
            $instance->setNumber($row['number']);
        }

        if (isset($row['course_id'])) {
            $instance->setCourseId($row['course_id']);
        }

        if (isset($row['customer_id'])) {
            $instance->setCustomerId($row['customer_id']);
        }

        if (isset($row['status'])) {
            $instance->setStatus($row['status']);
        }

        if (isset($row['payment'])) {
            $instance->setPayment($row['payment']);
        }

        if (isset($row['option_title'])) {
            $instance->setOptionTitle($row['option_title']);
        }

        if (isset($row['option_price'])) {
            $instance->setOptionPrice($row['option_price']);
        }

        if (isset($row['option_location'])) {
            $instance->setOptionLocation($row['option_location']);
        }

        return $instance;
    }

    public static function fromJson(array $row): CourseReservation
    {
        $instance = new CourseReservation();

        if (isset($row['id'])) {
            $instance->setId($row['id']);
        }

        if (isset($row['optionDates'])) {
            $instance->setOptionDates($row['optionDates']);
        }

        if (isset($row['createdAt'])) {
            $instance->setCreatedAt(new Carbon($row['createdAt']));
        }

        if (isset($row['updatedAt'])) {
            $instance->setUpdatedAt(new Carbon($row['updatedAt']));
        }

        if (isset($row['number'])) {
            $instance->setNumber($row['number']);
        }

        if (isset($row['courseId'])) {
            $instance->setCourseId($row['courseId']);
        }

        if (isset($row['customerId'])) {
            $instance->setCustomerId($row['customerId']);
        }

        if (isset($row['status'])) {
            $instance->setStatus($row['status']);
        }

        if (isset($row['payment'])) {
            $instance->setPayment($row['payment']);
        }

        if (isset($row['optionTitle'])) {
            $instance->setOptionTitle($row['optionTitle']);
        }

        if (isset($row['optionPrice'])) {
            $instance->setOptionPrice($row['optionPrice']);
        }

        if (isset($row['optionLocation'])) {
            $instance->setOptionLocation($row['optionLocation']);
        }

        return $instance;
    }

    public function toDatabase(): array
    {
        return [
            'id' => $this->id,
            'option_dates' => json_encode($this->optionDates),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
            'number' => $this->number,
            'course_id' => $this->courseId,
            'customer_id' => $this->customerId,
            'status' => $this->status,
            'payment' => $this->payment,
            'option_title' => $this->optionTitle,
            'option_price' => $this->optionPrice,
            'option_location' => $this->optionLocation,
        ];
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'optionDates' => $this->optionDates,
            'createdAt' => $this->createdAt->format('Y-m-d\TH:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d\TH:i:s'),
            'number' => $this->number,
            'courseId' => $this->courseId,
            'customerId' => $this->customerId,
            'status' => $this->status,
            'payment' => $this->payment,
            'optionTitle' => $this->optionTitle,
            'optionPrice' => $this->optionPrice,
            'optionLocation' => $this->optionLocation,
        ];
    }
}
