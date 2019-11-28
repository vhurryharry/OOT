<?php

declare(strict_types=1);

namespace App\Entity;

use Carbon\Carbon;
use JsonSerializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Notification implements JsonSerializable
{
    /**
     * @var int
     */
    protected $id;

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

    /**
     * @var string
     */
    protected $title;

    /**
     * @var ?string
     */
    protected $content;

    /**
     * @var ?string
     */
    protected $contentRich;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $event;

    /**
     * @var ?string
     */
    protected $fromEmail;

    /**
     * @var ?string
     */
    protected $fromName;

    /**
     * @var ?string
     */
    protected $fromNumber;

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

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContentRich(): ?string
    {
        return $this->contentRich;
    }

    public function setContentRich(string $contentRich): void
    {
        $this->contentRich = $contentRich;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getEvent(): string
    {
        return $this->event;
    }

    public function setEvent(string $event): void
    {
        $this->event = $event;
    }

    public function getFromEmail(): ?string
    {
        return $this->fromEmail;
    }

    public function setFromEmail(string $fromEmail): void
    {
        $this->fromEmail = $fromEmail;
    }

    public function getFromName(): ?string
    {
        return $this->fromName;
    }

    public function setFromName(string $fromName): void
    {
        $this->fromName = $fromName;
    }

    public function getFromNumber(): ?string
    {
        return $this->fromNumber;
    }

    public function setFromNumber(string $fromNumber): void
    {
        $this->fromNumber = $fromNumber;
    }

    public static function fromDatabase(array $row): Notification
    {
        $instance = new Notification();

        if (isset($row['id'])) {
            $instance->setId($row['id']);
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

        if (isset($row['title'])) {
            $instance->setTitle($row['title']);
        }

        if (isset($row['content'])) {
            $instance->setContent($row['content']);
        }

        if (isset($row['content_rich'])) {
            $instance->setContentRich($row['content_rich']);
        }

        if (isset($row['type'])) {
            $instance->setType($row['type']);
        }

        if (isset($row['event'])) {
            $instance->setEvent($row['event']);
        }

        if (isset($row['from_email'])) {
            $instance->setFromEmail($row['from_email']);
        }

        if (isset($row['from_name'])) {
            $instance->setFromName($row['from_name']);
        }

        if (isset($row['from_number'])) {
            $instance->setFromNumber($row['from_number']);
        }

        return $instance;
    }

    public static function fromJson(array $row): Notification
    {
        $instance = new Notification();

        if (isset($row['id'])) {
            $instance->setId($row['id']);
        }

        if (isset($row['course']) && !empty($row['course'])) {
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

        if (isset($row['title'])) {
            $instance->setTitle($row['title']);
        }

        if (isset($row['content'])) {
            $instance->setContent($row['content']);
        }

        if (isset($row['contentRich'])) {
            $instance->setContentRich($row['contentRich']);
        }

        if (isset($row['type'])) {
            $instance->setType($row['type']);
        }

        if (isset($row['event'])) {
            $instance->setEvent($row['event']);
        }

        if (isset($row['fromEmail'])) {
            $instance->setFromEmail($row['fromEmail']);
        }

        if (isset($row['fromName'])) {
            $instance->setFromName($row['fromName']);
        }

        if (isset($row['fromNumber'])) {
            $instance->setFromNumber($row['fromNumber']);
        }

        return $instance;
    }

    public function toDatabase(): array
    {
        return [
            'id' => $this->id,
            'course' => $this->course,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deletedAt ? $this->deletedAt->format('Y-m-d H:i:s') : null,
            'title' => $this->title,
            'content' => $this->content,
            'content_rich' => $this->contentRich,
            'type' => $this->type,
            'event' => $this->event,
            'from_email' => $this->fromEmail,
            'from_name' => $this->fromName,
            'from_number' => $this->fromNumber,
        ];
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'course' => $this->course,
            'createdAt' => $this->createdAt->format('Y-m-d\TH:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d\TH:i:s'),
            'deletedAt' => $this->deletedAt ? $this->deletedAt->format('Y-m-d\TH:i:s') : null,
            'title' => $this->title,
            'content' => $this->content,
            'contentRich' => $this->contentRich,
            'type' => $this->type,
            'event' => $this->event,
            'fromEmail' => $this->fromEmail,
            'fromName' => $this->fromName,
            'fromNumber' => $this->fromNumber,
        ];
    }
}
