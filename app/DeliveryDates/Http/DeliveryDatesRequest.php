<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\DeliveryDates\Http;

use DateTime;
use Illuminate\Foundation\Http\FormRequest;
use MyParcelCom\Microservice\Model\Json\AddressJson;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class DeliveryDatesRequest extends FormRequest
{
    public function serviceCode(): mixed
    {
        return $this->input('data.service_code');
    }

    public function serviceOptionCodes(): mixed
    {
        return $this->input('data.service_option_codes');
    }

    /**
     * @throws UnknownProperties
     */
    public function address(): AddressJson
    {
        return new AddressJson($this->input('data.address'));
    }

    public function startDate(): DateTime
    {
        return new DateTime($this->input('data.start_date'));
    }

    public function endDate(): DateTime
    {
        return new DateTime($this->input('data.end_date'));
    }
}
