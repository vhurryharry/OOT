<?php

declare(strict_types=1);

namespace App\Entity;

use Carbon\Carbon;
use JsonSerializable;

class CourseReservationHistory implements JsonSerializable
{
    /**
     * @var ?int
     */
    protected $reservation;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var Carbon
     */
    protected $createdAt;

    public function getReservation(): ?int
    {
        return $this->reservation;
    }

    public function setReservation(int $reservation): void
    {
        $this->reservation = $reservation;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function setCreatedAt(Carbon $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public static function fromDatabase(array $row): CourseReservationHistory
    {
        $instance = new CourseReservationHistory();

        if (isset($row['reservation'])) {
            $instance->setReservation($row['reservation']);
        }

        if (isset($row['status'])) {
            $instance->setStatus($row['status']);
        }

        if (isset($row['created_at'])) {
            $instance->setCreatedAt(new Carbon($row['created_at']));
        }

        return $instance;
    }

    public static function fromJson(array $row): CourseReservationHistory
    {
        $instance = new CourseReservationHistory();

        if (isset($row['reservation'])) {
            $instance->setReservation($row['reservation']);
        }

        if (isset($row['status'])) {
            $instance->setStatus($row['status']);
        }

        if (isset($row['createdAt'])) {
            $instance->setCreatedAt(new Carbon($row['createdAt']));
        }

        return $instance;
    }

    public function toDatabase(): array
    {
        return [
            'reservation' => $this->reservation,
            'status' => $this->status,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }

    public function jsonSerialize(): array
    {
        return [
            'reservation' => $this->reservation,
            'status' => $this->status,
            'createdAt' => $this->createdAt->format('Y-m-d\TH:i:s'),
        ];
    }
}
