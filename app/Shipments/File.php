<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Shipments;

class File
{
    private string $type;
    private ?string $mimeType = null;
    private ?string $extension = null;
    private string $data;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(?string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get the base64 encoded file data.
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * Set the file data (should be base64 encoded string).
     */
    public function setData(string $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function setDataFromPath(string $path): self
    {
        $this->data = base64_encode(
            file_get_contents($path),
        );

        return $this;
    }
}
