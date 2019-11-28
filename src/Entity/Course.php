<?php

declare(strict_types=1);

namespace App\Entity;

use Carbon\Carbon;
use JsonSerializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Course implements JsonSerializable
{
    /**
     * @var UuidInterface
     */
    protected $id;

    /**
     * @var ?string
     */
    protected $program;

    /**
     * @var ?array
     */
    protected $metadata;

    /**
     * @var ?string
     */
    protected $location;

    /**
     * @var ?string
     */
    protected $address;

    /**
     * @var ?string
     */
    protected $city;

    /**
     * @var Carbon
     */
    protected $startDate;

    /**
     * @var int
     */
    protected $spots;

    /**
     * @var ?int
     */
    protected $displayOrder;

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
    protected $slug;

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
    protected $tagline;

    /**
     * @var ?string
     */
    protected $metaTitle;

    /**
     * @var ?string
     */
    protected $metaDescription;

    /**
     * @var ?string
     */
    protected $thumbnail;

    /**
     * @var ?string
     */
    protected $hero;

    /**
     * @var ?string
     */
    protected $content;

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

    public function getProgram(): ?string
    {
        return $this->program;
    }

    public function setProgram(string $program): void
    {
        $this->program = $program;
    }

    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function setMetadata(array $metadata): void
    {
        $this->metadata = $metadata;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getStartDate(): Carbon
    {
        return $this->startDate;
    }

    public function setStartDate(Carbon $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getSpots(): int
    {
        return $this->spots;
    }

    public function setSpots(int $spots): void
    {
        $this->spots = $spots;
    }

    public function getDisplayOrder(): ?int
    {
        return $this->displayOrder;
    }

    public function setDisplayOrder(int $displayOrder): void
    {
        $this->displayOrder = $displayOrder;
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

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
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

    public function getTagline(): ?string
    {
        return $this->tagline;
    }

    public function setTagline(string $tagline): void
    {
        $this->tagline = $tagline;
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

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(string $thumbnail): void
    {
        $this->thumbnail = $thumbnail;
    }

    public function getHero(): ?string
    {
        return $this->hero;
    }

    public function setHero(string $hero): void
    {
        $this->hero = $hero;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public static function fromDatabase(array $row): Course
    {
        $instance = new Course();

        if (isset($row['id'])) {
            $instance->setId(Uuid::fromString($row['id']));
        }

        if (isset($row['program'])) {
            $instance->setProgram($row['program']);
        }

        if (isset($row['metadata'])) {
            $instance->setMetadata((array) json_decode($row['metadata'], true));
        }

        if (isset($row['location'])) {
            $instance->setLocation($row['location']);
        }

        if (isset($row['address'])) {
            $instance->setAddress($row['address']);
        }

        if (isset($row['city'])) {
            $instance->setCity($row['city']);
        }

        if (isset($row['start_date'])) {
            $instance->setStartDate(new Carbon($row['start_date']));
        }

        if (isset($row['spots'])) {
            $instance->setSpots($row['spots']);
        }

        if (isset($row['display_order'])) {
            $instance->setDisplayOrder($row['display_order']);
        }

        if (isset($row['created_at'])) {
            $instance->setCreatedAt(new Carbon($row['created_at']));
        }

        if (isset($row['updated_at'])) {
            $instance->setUpdatedAt(new Carbon($row['updated_at']));
        }

        if (isset($row['slug'])) {
            $instance->setSlug($row['slug']);
        }

        if (isset($row['deleted_at'])) {
            $instance->setDeletedAt(new Carbon($row['deleted_at']));
        }

        if (isset($row['title'])) {
            $instance->setTitle($row['title']);
        }

        if (isset($row['tagline'])) {
            $instance->setTagline($row['tagline']);
        }

        if (isset($row['meta_title'])) {
            $instance->setMetaTitle($row['meta_title']);
        }

        if (isset($row['meta_description'])) {
            $instance->setMetaDescription($row['meta_description']);
        }

        if (isset($row['thumbnail'])) {
            $instance->setThumbnail($row['thumbnail']);
        }

        if (isset($row['hero'])) {
            $instance->setHero($row['hero']);
        }

        if (isset($row['content'])) {
            $instance->setContent($row['content']);
        }

        return $instance;
    }

    public static function fromJson(array $row): Course
    {
        $instance = new Course();

        if (isset($row['id'])) {
            $instance->setId(Uuid::fromString($row['id']));
        }

        if (isset($row['program'])) {
            $instance->setProgram($row['program']);
        }

        if (isset($row['metadata'])) {
            $instance->setMetadata($row['metadata']);
        }

        if (isset($row['location'])) {
            $instance->setLocation($row['location']);
        }

        if (isset($row['address'])) {
            $instance->setAddress($row['address']);
        }

        if (isset($row['city'])) {
            $instance->setCity($row['city']);
        }

        if (isset($row['startDate'])) {
            $instance->setStartDate(new Carbon($row['startDate']));
        }

        if (isset($row['spots'])) {
            $instance->setSpots($row['spots']);
        }

        if (isset($row['displayOrder'])) {
            $instance->setDisplayOrder($row['displayOrder']);
        }

        if (isset($row['createdAt'])) {
            $instance->setCreatedAt(new Carbon($row['createdAt']));
        }

        if (isset($row['updatedAt'])) {
            $instance->setUpdatedAt(new Carbon($row['updatedAt']));
        }

        if (isset($row['slug'])) {
            $instance->setSlug($row['slug']);
        }

        if (isset($row['deletedAt'])) {
            $instance->setDeletedAt(new Carbon($row['deletedAt']));
        }

        if (isset($row['title'])) {
            $instance->setTitle($row['title']);
        }

        if (isset($row['tagline'])) {
            $instance->setTagline($row['tagline']);
        }

        if (isset($row['metaTitle'])) {
            $instance->setMetaTitle($row['metaTitle']);
        }

        if (isset($row['metaDescription'])) {
            $instance->setMetaDescription($row['metaDescription']);
        }

        if (isset($row['thumbnail'])) {
            $instance->setThumbnail($row['thumbnail']);
        }

        if (isset($row['hero'])) {
            $instance->setHero($row['hero']);
        }

        if (isset($row['content'])) {
            $instance->setContent($row['content']);
        }

        return $instance;
    }

    public function toDatabase(): array
    {
        return [
            'id' => $this->id,
            'program' => $this->program,
            'metadata' => json_encode($this->metadata),
            'location' => $this->location,
            'address' => $this->address,
            'city' => $this->city,
            'start_date' => $this->startDate->format('Y-m-d H:i:s'),
            'spots' => $this->spots,
            'display_order' => $this->displayOrder,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
            'slug' => $this->slug,
            'deleted_at' => $this->deletedAt ? $this->deletedAt->format('Y-m-d H:i:s') : null,
            'title' => $this->title,
            'tagline' => $this->tagline,
            'meta_title' => $this->metaTitle,
            'meta_description' => $this->metaDescription,
            'thumbnail' => $this->thumbnail,
            'hero' => $this->hero,
            'content' => $this->content,
        ];
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'program' => $this->program,
            'metadata' => $this->metadata,
            'location' => $this->location,
            'address' => $this->address,
            'city' => $this->city,
            'startDate' => $this->startDate->format('Y-m-d\TH:i:s'),
            'spots' => $this->spots,
            'displayOrder' => $this->displayOrder,
            'createdAt' => $this->createdAt->format('Y-m-d\TH:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d\TH:i:s'),
            'slug' => $this->slug,
            'deletedAt' => $this->deletedAt ? $this->deletedAt->format('Y-m-d\TH:i:s') : null,
            'title' => $this->title,
            'tagline' => $this->tagline,
            'metaTitle' => $this->metaTitle,
            'metaDescription' => $this->metaDescription,
            'thumbnail' => $this->thumbnail,
            'hero' => $this->hero,
            'content' => $this->content,
        ];
    }
}
