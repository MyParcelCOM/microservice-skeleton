<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\ServiceRates;

class Price
{

    protected int $amount;

    protected string $currency;

    /**
     * @param int          $amount
     * @param string $currency
     */
    public function __construct(int $amount, string $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
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
