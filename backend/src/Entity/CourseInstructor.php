<?php

declare(strict_types=1);

namespace App\Entity;

use Carbon\Carbon;
use JsonSerializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class CourseInstructor implements JsonSerializable
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var UuidInterface
     */
    protected $courseId;

    /**
     * @var UuidInterface
     */
    protected $customerId;

    public function __construct()
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCourseId(): UuidInterface
    {
        return $this->courseId;
    }

    public function setCourseId(UuidInterface $courseId): void
    {
        $this->courseId = $courseId;
    }

    public function getCustomerId(): UuidInterface
    {
        return $this->customerId;
    }

    public function setCustomerId(UuidInterface $customerId): void
    {
        $this->customerId = $customerId;
    }

    public static function fromDatabase(array $row): CourseInstructor
    {
        $instance = new CourseInstructor();

        if (isset($row['id'])) {
            $instance->setId($row['id']);
        }

        if (isset($row['course_id'])) {
            $instance->setCourseId(Uuid::fromString($row['course_id']));
        }

        if (isset($row['customer_id'])) {
            $instance->setCustomerId(Uuid::fromString($row['customer_id']));
        }

        return $instance;
    }

    public static function fromJson(array $row): CourseInstructor
    {
        $instance = new CourseInstructor();

        if (isset($row['id'])) {
            $instance->setId($row['id']);
        }

        if (isset($row['courseId'])) {
            $instance->setCourseId(Uuid::fromString($row['courseId']));
        }

        if (isset($row['customerId'])) {
            $instance->setCustomerId(Uuid::fromString($row['customerId']));
        }

        return $instance;
    }

    public function toDatabase(): array
    {
        return [
            'id' => $this->id,
            'course_id' => $this->courseId,
            'customer_id' => $this->customerId,
        ];
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'courseId' => $this->courseId,
            'customerId' => $this->customerId,
        ];
    }
}
