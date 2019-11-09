<?php

declare(strict_types=1);

namespace App\Entity;

use Carbon\Carbon;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;

class Address implements JsonSerializable
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $country;

    /**
     * @var string
     */
    protected $zipcode;

    /**
     * @var ?string
     */
    protected $company;

    /**
     * @var ?string
     */
    protected $phone;

    /**
     * @var ?string
     */
    protected $taxId;

    /**
     * @var ?string
     */
    protected $docType;

    /**
     * @var ?string
     */
    protected $docNumber;

    /**
     * @var ?array
     */
    protected $metadata;

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
    protected $type;

    /**
     * @var ?Carbon
     */
    protected $deletedAt;

    /**
     * @var ?UuidInterface
     */
    protected $customer;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $line1;

    /**
     * @var ?string
     */
    protected $line2;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $state;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function getZipcode(): string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): void
    {
        $this->zipcode = $zipcode;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): void
    {
        $this->company = $company;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getTaxId(): ?string
    {
        return $this->taxId;
    }

    public function setTaxId(string $taxId): void
    {
        $this->taxId = $taxId;
    }

    public function getDocType(): ?string
    {
        return $this->docType;
    }

    public function setDocType(string $docType): void
    {
        $this->docType = $docType;
    }

    public function getDocNumber(): ?string
    {
        return $this->docNumber;
    }

    public function setDocNumber(string $docNumber): void
    {
        $this->docNumber = $docNumber;
    }

    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function setMetadata(array $metadata): void
    {
        $this->metadata = $metadata;
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

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getDeletedAt(): ?Carbon
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(Carbon $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    public function getCustomer(): ?UuidInterface
    {
        return $this->customer;
    }

    public function setCustomer(UuidInterface $customer): void
    {
        $this->customer = $customer;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getLine1(): string
    {
        return $this->line1;
    }

    public function setLine1(string $line1): void
    {
        $this->line1 = $line1;
    }

    public function getLine2(): ?string
    {
        return $this->line2;
    }

    public function setLine2(string $line2): void
    {
        $this->line2 = $line2;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public static function fromDatabase(array $row): Address
    {
        $instance = new Address();

        if (isset($row['id'])) {
            $instance->setId($row['id']);
        }

        if (isset($row['country'])) {
            $instance->setCountry($row['country']);
        }

        if (isset($row['zipcode'])) {
            $instance->setZipcode($row['zipcode']);
        }

        if (isset($row['company'])) {
            $instance->setCompany($row['company']);
        }

        if (isset($row['phone'])) {
            $instance->setPhone($row['phone']);
        }

        if (isset($row['tax_id'])) {
            $instance->setTaxId($row['tax_id']);
        }

        if (isset($row['doc_type'])) {
            $instance->setDocType($row['doc_type']);
        }

        if (isset($row['doc_number'])) {
            $instance->setDocNumber($row['doc_number']);
        }

        if (isset($row['metadata'])) {
            $instance->setMetadata(json_decode($row['metadata'], true));
        }

        if (isset($row['created_at'])) {
            $instance->setCreatedAt(new Carbon($row['created_at']));
        }

        if (isset($row['updated_at'])) {
            $instance->setUpdatedAt(new Carbon($row['updated_at']));
        }

        if (isset($row['type'])) {
            $instance->setType($row['type']);
        }

        if (isset($row['deleted_at'])) {
            $instance->setDeletedAt(new Carbon($row['deleted_at']));
        }

        if (isset($row['customer'])) {
            $instance->setCustomer($row['customer']);
        }

        if (isset($row['first_name'])) {
            $instance->setFirstName($row['first_name']);
        }

        if (isset($row['last_name'])) {
            $instance->setLastName($row['last_name']);
        }

        if (isset($row['line1'])) {
            $instance->setLine1($row['line1']);
        }

        if (isset($row['line2'])) {
            $instance->setLine2($row['line2']);
        }

        if (isset($row['city'])) {
            $instance->setCity($row['city']);
        }

        if (isset($row['state'])) {
            $instance->setState($row['state']);
        }

        return $instance;
    }

    public function toDatabase(): array
    {
        return [
            'id' => $this->id,
            'country' => $this->country,
            'zipcode' => $this->zipcode,
            'company' => $this->company,
            'phone' => $this->phone,
            'tax_id' => $this->taxId,
            'doc_type' => $this->docType,
            'doc_number' => $this->docNumber,
            'metadata' => json_encode($this->metadata),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
            'type' => $this->type,
            'deleted_at' => $this->deletedAt ? $this->deletedAt->format('Y-m-d H:i:s') : null,
            'customer' => $this->customer,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'line1' => $this->line1,
            'line2' => $this->line2,
            'city' => $this->city,
            'state' => $this->state,
        ];
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'country' => $this->country,
            'zipcode' => $this->zipcode,
            'company' => $this->company,
            'phone' => $this->phone,
            'taxId' => $this->taxId,
            'docType' => $this->docType,
            'docNumber' => $this->docNumber,
            'metadata' => $this->metadata,
            'createdAt' => $this->createdAt->format('Y-m-d\TH:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d\TH:i:s'),
            'type' => $this->type,
            'deletedAt' => $this->deletedAt ? $this->deletedAt->format('Y-m-d\TH:i:s') : null,
            'customer' => $this->customer,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'line1' => $this->line1,
            'line2' => $this->line2,
            'city' => $this->city,
            'state' => $this->state,
        ];
    }
}
