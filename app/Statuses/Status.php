<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Statuses;

use MyParcelCom\Microservice\Shipments\PhysicalProperties;

class Status
{
    private ?string $id = null;
    private ?string $code = null;
    private string $category = 'default';
    private ?string $description = null;
    private ?int $timestamp = null;
    private ?PhysicalProperties $physicalProperties = null;
    private ?string $newTrackingCode = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }

    public function setTimestamp(int $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getPhysicalProperties(): ?PhysicalProperties
    {
        return $this->physicalProperties;
    }

    public function setPhysicalProperties(PhysicalProperties $physicalProperties): self
    {
        $this->physicalProperties = $physicalProperties;

        return $this;
    }

    public function getNewTrackingCode(): string|null
    {
        return $this->newTrackingCode;
    }

    public function setNewTrackingCode(string $newTrackingCode): self
    {
        $this->newTrackingCode = $newTrackingCode;

        return $this;
    }
}
