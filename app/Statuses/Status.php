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

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     * @return $this
     */
    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param string $category
     * @return $this
     */
    public function setCategory(string $category): self
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }

    /**
     * @param int $timestamp
     * @return $this
     */
    public function setTimestamp(int $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * @return PhysicalProperties|null
     */
    public function getPhysicalProperties(): ?PhysicalProperties
    {
        return $this->physicalProperties;
    }

    /**
     * @param PhysicalProperties $physicalProperties
     * @return $this
     */
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
