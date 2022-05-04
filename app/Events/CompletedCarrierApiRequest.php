<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Events;

class CompletedCarrierApiRequest
{
    /**
     * @param null|string $response
     */
    public function __construct(private ?string $response = null)
    {
        $this->response = $response;
    }

    /**
     * @return string|null
     */
    public function getResponse(): ?string
    {
        return $this->response;
    }
}
