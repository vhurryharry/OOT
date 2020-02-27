<?php

declare(strict_types=1);

namespace App\Security;

use Carbon\Carbon;
use JsonSerializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class Customer implements UserInterface, JsonSerializable
{
    public const ACTIVE = 'active';
    public const INACTIVE = 'inactive';
    public const PENDING_CONFIRMATION = 'pending_confirmation';
    public const PENDING_PASSWORD_RESET = 'pending_password_reset';
    public const SUSPENDED = 'suspended';
    public const LOCKED = 'locked';
    public const PASSWORD_EXPIRED = 'password_expired';

    /**
     * @var UuidInterface
     */
    protected $id;

    /**
     * @var ?string
     */
    protected $login;

    /**
     * @var ?string
     */
    protected $password;

    /**
     * @var ?string
     */
    protected $rawPassword;

    /**
     * @var string[]
     */
    protected $roles = [];

    /**
     * @var ?array
     */
    protected $metadata;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $status = self::ACTIVE;

    /**
     * @var ?string
     */
    protected $confirmationToken;

    /**
     * @var bool
     */
    protected $acceptsMarketing = false;

    /**
     * @var ?string
     */
    protected $bio;

    /**
     * @var ?string
     */
    protected $website;

    /**
     * @var ?string
     */
    protected $instagram;

    /**
     * @var ?string
     */
    protected $twitter;

    /**
     * @var ?string
     */
    protected $facebook;

    /**
     * @var Carbon
     */
    protected $createdAt;

    /**
     * @var Carbon
     */
    protected $registeredAt;

    /**
     * @var Carbon
     */
    protected $updatedAt;

    /**
     * @var ?Carbon
     */
    protected $deletedAt;

    /**
     * @var ?Carbon
     */
    protected $expiresAt;

    /**
     * @var ?Carbon
     */
    protected $passwordExpiresAt;

    /**
     * @var ?string
     */
    protected $firstName;

    /**
     * @var ?string
     */
    protected $lastName;

    /**
     * @var ?string
     */
    protected $tagline;

    /**
     * @var ?string
     */
    protected $occupation;

    /**
     * @var ?Carbon
     */
    protected $birthDate;

    /**
     * @var ?string
     */
    protected $mfa;

    /**
     * @var ?bytea
     */
    protected $avatar;

    public function __construct(string $login = null, UuidInterface $id = null)
    {
        $this->id = $id ?? Uuid::uuid4();
        $this->login = $login;
        $this->createdAt = Carbon::now();
        $this->updatedAt = $this->createdAt;
        $this->registeredAt = $this->createdAt;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getUsername(): string
    {
        return (string) $this->login;
    }

    public function getEmail(): string
    {
        return (string) $this->login;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return $roles;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRawPassword(): ?string
    {
        return $this->rawPassword;
    }

    public function setRawPassword(string $rawPassword): void
    {
        $this->rawPassword = $rawPassword;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        $this->rawPassword = null;
    }

    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function setMetadata(?array $metadata): void
    {
        $this->metadata = $metadata;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken(string $confirmationToken): void
    {
        $this->confirmationToken = $confirmationToken;
    }

    public function acceptsMarketing(): bool
    {
        return $this->acceptsMarketing;
    }

    public function setAcceptsMarketing(bool $acceptsMarketing): void
    {
        $this->acceptsMarketing = $acceptsMarketing;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(string $bio): void
    {
        $this->bio = $bio;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(string $website): void
    {
        $this->website = $website;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(string $instagram): void
    {
        $this->instagram = $instagram;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(string $twitter): void
    {
        $this->twitter = $twitter;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(string $facebook): void
    {
        $this->facebook = $facebook;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function setCreatedAt(Carbon $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getRegisteredAt(): Carbon
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(Carbon $registeredAt): void
    {
        $this->registeredAt = $registeredAt;
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

    public function getExpiresAt(): ?Carbon
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(Carbon $expiresAt): void
    {
        $this->expiresAt = $expiresAt;
    }

    public function getPasswordExpiresAt(): ?Carbon
    {
        return $this->passwordExpiresAt;
    }

    public function setPasswordExpiresAt(Carbon $passwordExpiresAt): void
    {
        $this->passwordExpiresAt = $passwordExpiresAt;
    }

    public function getName(): ?string
    {
        if (!$this->firstName) {
            return $this->login;
        }

        return $this->firstName . ' ' . $this->lastName;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getTagline(): ?string
    {
        return $this->tagline;
    }

    public function setTagline(string $tagline): void
    {
        $this->tagline = $tagline;
    }

    public function getOccupation(): ?string
    {
        return $this->occupation;
    }

    public function setOccupation(string $occupation): void
    {
        $this->occupation = $occupation;
    }

    public function getBirthDate(): ?Carbon
    {
        return $this->birthDate;
    }

    public function setBirthDate(Carbon $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    public function getMfa(): ?string
    {
        return $this->mfa;
    }

    public function setMfa(string $mfa): void
    {
        $this->mfa = $mfa;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    public static function fromJson(array $row): Customer
    {
        $instance = new Customer($row['login']);

        if (isset($row['id'])) {
            $instance->setId(Uuid::fromString($row['id']));
        }

        if (isset($row['metadata'])) {
            $instance->setMetadata($row['metadata']);
        }

        if (isset($row['type'])) {
            $instance->setType($row['type']);
        }

        if (isset($row['status'])) {
            $instance->setStatus($row['status']);
        }

        if (isset($row['confirmationToken'])) {
            $instance->setConfirmationToken($row['confirmationToken']);
        }

        if (isset($row['acceptsMarketing'])) {
            $instance->setAcceptsMarketing((bool) $row['acceptsMarketing']);
        }

        if (isset($row['bio'])) {
            $instance->setBio($row['bio']);
        }

        if (isset($row['website'])) {
            $instance->setWebsite($row['website']);
        }

        if (isset($row['instagram'])) {
            $instance->setInstagram($row['instagram']);
        }

        if (isset($row['facebook'])) {
            $instance->setFacebook($row['facebook']);
        }

        if (isset($row['twitter'])) {
            $instance->setTwitter($row['twitter']);
        }

        if (isset($row['createdAt'])) {
            $instance->setCreatedAt(new Carbon($row['createdAt']));
        }

        if (isset($row['registeredAt'])) {
            $instance->setRegisteredAt(new Carbon($row['registeredAt']));
        }

        if (isset($row['updatedAt'])) {
            $instance->setUpdatedAt(new Carbon($row['updatedAt']));
        }

        if (isset($row['deletedAt'])) {
            $instance->setDeletedAt(new Carbon($row['deletedAt']));
        }

        if (isset($row['expiresAt'])) {
            $instance->setExpiresAt(new Carbon($row['expiresAt']));
        }

        if (isset($row['passwordExpiresAt'])) {
            $instance->setPasswordExpiresAt(new Carbon($row['passwordExpiresAt']));
        }

        if (isset($row['firstName'])) {
            $instance->setFirstName($row['firstName']);
        }

        if (isset($row['lastName'])) {
            $instance->setLastName($row['lastName']);
        }

        if (isset($row['tagline'])) {
            $instance->setTagline($row['tagline']);
        }

        if (isset($row['occupation'])) {
            $instance->setOccupation($row['occupation']);
        }

        if (isset($row['birthDate'])) {
            $instance->setBirthDate(new Carbon($row['birthDate']));
        }

        if (isset($row['password'])) {
            $instance->setPassword($row['password']);
        }

        if (isset($row['mfa'])) {
            $instance->setMfa($row['mfa']);
        }

        if (isset($row['avatar'])) {
            $instance->setAvatar($row['avatar']);
        }

        return $instance;
    }

    public static function fromDatabase(array $row): Customer
    {
        $instance = new Customer($row['login'], Uuid::fromString($row['id']));

        if (isset($row['password'])) {
            $instance->setPassword($row['password']);
        }

        if (isset($row['metadata'])) {
            $instance->setMetadata(json_decode($row['metadata'], true));
        }

        if (isset($row['type'])) {
            $instance->setType($row['type']);
        }

        if (isset($row['status'])) {
            $instance->setStatus($row['status']);
        }

        if (isset($row['confirmation_token'])) {
            $instance->setConfirmationToken($row['confirmation_token']);
        }

        if (isset($row['accepts_marketing'])) {
            $instance->setAcceptsMarketing($row['accepts_marketing']);
        }

        if (isset($row['bio'])) {
            $instance->setBio($row['bio']);
        }

        if (isset($row['website'])) {
            $instance->setWebsite($row['website']);
        }

        if (isset($row['instagram'])) {
            $instance->setInstagram($row['instagram']);
        }

        if (isset($row['facebook'])) {
            $instance->setFacebook($row['facebook']);
        }

        if (isset($row['twitter'])) {
            $instance->setTwitter($row['twitter']);
        }

        if (isset($row['created_at'])) {
            $instance->setCreatedAt(new Carbon($row['created_at']));
        }

        if (isset($row['registered_at'])) {
            $instance->setRegisteredAt(new Carbon($row['registered_at']));
        }

        if (isset($row['updated_at'])) {
            $instance->setUpdatedAt(new Carbon($row['updated_at']));
        }

        if (isset($row['deleted_at'])) {
            $instance->setDeletedAt(new Carbon($row['deleted_at']));
        }

        if (isset($row['expires_at'])) {
            $instance->setExpiresAt(new Carbon($row['expires_at']));
        }

        if (isset($row['password_expires_at'])) {
            $instance->setPasswordExpiresAt(new Carbon($row['password_expires_at']));
        }

        if (isset($row['first_name'])) {
            $instance->setFirstName($row['first_name']);
        }

        if (isset($row['last_name'])) {
            $instance->setLastName($row['last_name']);
        }

        if (isset($row['tagline'])) {
            $instance->setTagline($row['tagline']);
        }

        if (isset($row['occupation'])) {
            $instance->setOccupation($row['occupation']);
        }

        if (isset($row['birth_date'])) {
            $instance->setBirthDate(new Carbon($row['birth_date']));
        }

        if (isset($row['mfa'])) {
            $instance->setMfa($row['mfa']);
        }

        if (isset($row['avatar'])) {
            $instance->setAvatar($row['avatar']);
        }

        return $instance;
    }

    public function toDatabase(): array
    {
        return [
            'id' => $this->id,
            'metadata' => json_encode($this->metadata),
            'type' => $this->type,
            'status' => $this->status,
            'confirmation_token' => $this->confirmationToken,
            'accepts_marketing' => $this->acceptsMarketing,
            'bio' => $this->bio,
            'website' => $this->website,
            'instagram' => $this->instagram,
            'twitter' => $this->twitter,
            'facebook' => $this->facebook,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'registered_at' => $this->registeredAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deletedAt ? $this->deletedAt->format('Y-m-d H:i:s') : null,
            'expires_at' => $this->expiresAt ? $this->expiresAt->format('Y-m-d H:i:s') : null,
            'login' => $this->login,
            'password_expires_at' => $this->passwordExpiresAt ? $this->passwordExpiresAt->format('Y-m-d H:i:s') : null,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'tagline' => $this->tagline,
            'occupation' => $this->occupation,
            'birth_date' => $this->birthDate ? $this->birthDate->format('Y-m-d H:i:s') : null,
            'password' => $this->password,
            'mfa' => $this->mfa,
            'avatar' => $this->avatar,
        ];
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'metadata' => $this->metadata,
            'type' => $this->type,
            'status' => $this->status,
            'confirmationToken' => $this->confirmationToken,
            'acceptsMarketing' => $this->acceptsMarketing,
            'bio' => $this->bio,
            'website' => $this->website,
            'instagram' => $this->instagram,
            'twitter' => $this->twitter,
            'facebook' => $this->facebook,
            'createdAt' => $this->createdAt->format('Y-m-d\TH:i:s'),
            'registeredAt' => $this->registeredAt->format('Y-m-d\TH:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d\TH:i:s'),
            'deletedAt' => $this->deletedAt ? $this->deletedAt->format('Y-m-d\TH:i:s') : null,
            'expiresAt' => $this->expiresAt ? $this->expiresAt->format('Y-m-d\TH:i:s') : null,
            'login' => $this->login,
            'passwordExpiresAt' => $this->passwordExpiresAt ? $this->passwordExpiresAt->format('Y-m-d\TH:i:s') : null,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'tagline' => $this->tagline,
            'occupation' => $this->occupation,
            'birthDate' => $this->birthDate ? $this->birthDate->format('Y-m-d\TH:i:s') : null,
            'password' => $this->password,
            'mfa' => $this->mfa,
            'avatar' => $this->avatar,
        ];
    }
}
