<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Events;

class CompletedCarrierApiRequest
{
    /**
     * @param mixed $response
     */
    public function __construct(private $response = null)
    {
        $this->response = $response;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }
}
