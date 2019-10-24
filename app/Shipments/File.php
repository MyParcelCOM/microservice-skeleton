<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Shipments;

class File
{
    /** @var string */
    private $type;

    /** @var string */
    private $mimeType;

    /** @var string */
    private $extension;

    /** @var string */
    private $data;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * @param string $mimeType
     * @return $this
     */
    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getExtension(): ?string
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     * @return $this
     */
    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get the base64 encoded file data.
     *
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * Set the file data (should be base64 encoded string).
     *
     * @param string $data
     * @return $this
     */
    public function setData(string $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setDataFromPath(string $path): self
    {
        $this->data = base64_encode(
            file_get_contents($path)
        );

        return $this;
    }
}
