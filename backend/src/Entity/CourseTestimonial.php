<?php

declare(strict_types=1);

namespace App\Entity;

use Carbon\Carbon;
use JsonSerializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class CourseTestimonial implements JsonSerializable
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $testimonial;

    /**
     * @var string
     */
    protected $author;

    /**
     * @var string
     */
    protected $authorOccupation;

    /**
     * @var string
     */
    protected $authorAvatar;

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

    public function getTestimonial(): string
    {
        return $this->testimonial;
    }

    public function setTestimonial(string $testimonial): void
    {
        $this->testimonial = $testimonial;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    public function getAuthorOccupation(): ?string
    {
        return $this->authorOccupation;
    }

    public function setAuthorOccupation(string $authorOccupation): void
    {
        $this->authorOccupation = $authorOccupation;
    }

    public function getAuthorAvatar(): ?string
    {
        return $this->authorAvatar;
    }

    public function setAuthorAvatar(string $authorAvatar): void
    {
        $this->authorAvatar = $authorAvatar;
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

    public static function fromDatabase(array $row): CourseTestimonial
    {
        $instance = new CourseTestimonial();

        if (isset($row['id'])) {
            $instance->setId($row['id']);
        }

        if (isset($row['testimonial'])) {
            $instance->setTestimonial($row['testimonial']);
        }

        if (isset($row['author'])) {
            $instance->setAuthor($row['author']);
        }

        if (isset($row['author_occupation'])) {
            $instance->setAuthorOccupation($row['author_occupation']);
        }

        if (isset($row['author_avatar'])) {
            $instance->setAuthorAvatar($row['author_avatar']);
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

    public static function fromJson(array $row): CourseTestimonial
    {
        $instance = new CourseTestimonial();

        if (isset($row['id'])) {
            $instance->setId($row['id']);
        }

        if (isset($row['testimonial'])) {
            $instance->setTestimonial($row['testimonial']);
        }

        if (isset($row['author'])) {
            $instance->setAuthor($row['author']);
        }

        if (isset($row['authorOccupation'])) {
            $instance->setAuthorOccupation($row['authorOccupation']);
        }

        if (isset($row['authorAvatar'])) {
            $instance->setAuthorAvatar($row['authorAvatar']);
        }

        if (isset($row['course']) && $row['course'] != '') {
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
            'testimonial' => $this->testimonial,
            'author' => $this->author,
            'author_occupation' => $this->authorOccupation,
            'author_avatar' => $this->authorAvatar,
            'course' => $this->course,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deletedAt ? $this->deletedAt->format('Y-m-d H:i:s') : null,
        ];
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'testimonial' => $this->testimonial,
            'author' => $this->author,
            'authorOccupation' => $this->authorOccupation,
            'authorAvatar' => $this->authorAvatar,
            'course' => $this->course,
            'createdAt' => $this->createdAt->format('Y-m-d\TH:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d\TH:i:s'),
            'deletedAt' => $this->deletedAt ? $this->deletedAt->format('Y-m-d\TH:i:s') : null,
        ];
    }
}
