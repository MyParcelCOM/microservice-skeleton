<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\ServiceRates;

class Price
{
    /**
     * @param int    $amount
     * @param string $currency
     */
    public function __construct(private int $amount, private string $currency)
    {
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }
}
