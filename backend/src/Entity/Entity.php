<?php

declare(strict_types=1);

namespace App\Entity;

use Carbon\Carbon;
use JsonSerializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Entity implements JsonSerializable
{
    /**
     * @var UuidInterface
     */
    protected $id;

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
    protected $slug;

    /**
     * @var ?int
     */
    protected $category;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var ?string
     */
    protected $content;

    /**
     * @var ?string
     */
    protected $metaTitle;

    /**
     * @var ?string
     */
    protected $metaDescription;

    /**
     * @var ?array
     */
    protected $metadata;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->createdAt = Carbon::now();
        $this->updatedAt = $this->createdAt;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
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

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getCategory(): ?int
    {
        return $this->category;
    }

    public function setCategory(int $category): void
    {
        $this->category = $category;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(string $metaTitle): void
    {
        $this->metaTitle = $metaTitle;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(string $metaDescription): void
    {
        $this->metaDescription = $metaDescription;
    }

    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function setMetadata(array $metadata): void
    {
        $this->metadata = $metadata;
    }

    public static function fromDatabase(array $row): Entity
    {
        $instance = new Entity();

        if (isset($row['id'])) {
            $instance->setId(Uuid::fromString($row['id']));
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

        if (isset($row['slug'])) {
            $instance->setSlug($row['slug']);
        }

        if (isset($row['category'])) {
            $instance->setCategory($row['category']);
        }

        if (isset($row['title'])) {
            $instance->setTitle($row['title']);
        }

        if (isset($row['type'])) {
            $instance->setType($row['type']);
        }

        if (isset($row['content'])) {
            $instance->setContent($row['content']);
        }

        if (isset($row['meta_title'])) {
            $instance->setMetaTitle($row['meta_title']);
        }

        if (isset($row['meta_description'])) {
            $instance->setMetaDescription($row['meta_description']);
        }

        if (isset($row['metadata'])) {
            $instance->setMetadata((array) json_decode($row['metadata'], true));
        }

        return $instance;
    }

    public static function fromJson(array $row): Entity
    {
        $instance = new Entity();

        if (isset($row['id'])) {
            $instance->setId(Uuid::fromString($row['id']));
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

        if (isset($row['slug'])) {
            $instance->setSlug($row['slug']);
        }

        if (isset($row['category'])) {
            $instance->setCategory((int) $row['category']);
        }

        if (isset($row['title'])) {
            $instance->setTitle($row['title']);
        }

        if (isset($row['type'])) {
            $instance->setType($row['type']);
        }

        if (isset($row['content'])) {
            $instance->setContent($row['content']);
        }

        if (isset($row['metaTitle'])) {
            $instance->setMetaTitle($row['metaTitle']);
        }

        if (isset($row['metaDescription'])) {
            $instance->setMetaDescription($row['metaDescription']);
        }

        if (isset($row['metadata'])) {
            $instance->setMetadata($row['metadata']);
        }

        return $instance;
    }

    public function toDatabase(): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deletedAt ? $this->deletedAt->format('Y-m-d H:i:s') : null,
            'slug' => $this->slug,
            'category' => $this->category,
            'title' => $this->title,
            'type' => $this->type,
            'content' => $this->content,
            'meta_title' => $this->metaTitle,
            'meta_description' => $this->metaDescription,
            'metadata' => json_encode($this->metadata),
        ];
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'createdAt' => $this->createdAt->format('Y-m-d\TH:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d\TH:i:s'),
            'deletedAt' => $this->deletedAt ? $this->deletedAt->format('Y-m-d\TH:i:s') : null,
            'slug' => $this->slug,
            'category' => $this->category,
            'title' => $this->title,
            'type' => $this->type,
            'content' => $this->content,
            'metaTitle' => $this->metaTitle,
            'metaDescription' => $this->metaDescription,
            'metadata' => $this->metadata,
        ];
    }
}
