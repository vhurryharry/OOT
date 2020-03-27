<?php

declare(strict_types=1);

namespace App\Entity;

use Carbon\Carbon;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

class SurveyResult implements JsonSerializable
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var UuidInterface
     */
    protected $customerId;

    /**
     * @var UuidInterface
     */
    protected $courseId;

    /**
     * @var int
     */
    protected $questionId;

    /**
     * @var string
     */
    protected $answer;

    /**
     * @var string
     */
    protected $extra;

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

    public function getCustomerId(): ?UuidInterface
    {
        return $this->customerId;
    }

    public function setCustomerId(UuidInterface $customerId): void
    {
        $this->customerId = $customerId;
    }

    public function getCourseId(): ?UuidInterface
    {
        return $this->courseId;
    }

    public function setCourseId(UuidInterface $courseId): void
    {
        $this->courseId = $courseId;
    }

    public function getQuestionId(): int
    {
        return $this->questionId;
    }

    public function setQuestionId(int $questionId): void
    {
        $this->questionId = $questionId;
    }

    public function getAnswer(): string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): void
    {
        $this->answer = $answer;
    }

    public function getExtra(): string
    {
        return $this->extra;
    }

    public function setExtra(string $extra): void
    {
        $this->extra = $extra;
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

    public static function fromDatabase(array $row): SurveyResult
    {
        $instance = new SurveyResult();

        if (isset($row['id'])) {
            $instance->setId($row['id']);
        }

        if (isset($row['customer_id'])) {
            $instance->setCustomerId(Uuid::fromString($row['customer_id']));
        }

        if (isset($row['question_id'])) {
            $instance->setQuestionId($row['question_id']);
        }

        if (isset($row['course_id'])) {
            $instance->setCourseId(Uuid::fromString($row['course_id']));
        }

        if (isset($row['answer'])) {
            $instance->setAnswer($row['answer']);
        }

        if (isset($row['extra'])) {
            $instance->setExtra($row['extra']);
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

    public static function fromJson(array $row): SurveyResult
    {
        $instance = new SurveyResult();

        if (isset($row['id'])) {
            $instance->setId($row['id']);
        }

        if (isset($row['customerId'])) {
            $instance->setCustomerId(Uuid::fromString($row['customerId']));
        }

        if (isset($row['questionId'])) {
            $instance->setQuestionId($row['questionId']);
        }

        if (isset($row['courseId'])) {
            $instance->setCourseId(Uuid::fromString($row['courseId']));
        }

        if (isset($row['answer'])) {
            $instance->setAnswer($row['answer']);
        }

        if (isset($row['extra'])) {
            $instance->setExtra($row['extra']);
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
            'customer_id' => $this->customerId,
            'question_id' => $this->questionId,
            'course_id' => $this->courseId,
            'answer' => $this->answer,
            'extra' => $this->extra,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deletedAt ? $this->deletedAt->format('Y-m-d H:i:s') : null,
        ];
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'customerId' => $this->customerId,
            'questionId' => $this->questionId,
            'courseId' => $this->courseId,
            'answer' => $this->answer,
            'extra' => $this->extra,
            'createdAt' => $this->createdAt->format('Y-m-d\TH:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d\TH:i:s'),
            'deletedAt' => $this->deletedAt ? $this->deletedAt->format('Y-m-d\TH:i:s') : null,
        ];
    }
}
