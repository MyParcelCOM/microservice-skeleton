<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\CollectionTimeSlots;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use MyParcelCom\JsonApi\Errors\InvalidInputError;
use MyParcelCom\JsonApi\Exceptions\InvalidInputException;
use MyParcelCom\JsonApi\Resources\CollectionResources;
use MyParcelCom\JsonApi\Resources\Interfaces\ResourcesInterface;
use MyParcelCom\JsonApi\Resources\PromiseResources;
use MyParcelCom\Microservice\Carrier\CarrierApiGatewayInterface;
use ReflectionException;
use RuntimeException;
use Yasumi\Exception\ProviderNotFoundException;
use Yasumi\Exception\UnknownLocaleException;
use Yasumi\Yasumi;

class CollectionTimeSlotRepository
{
    /**
     * In-memory cache for holiday checker objects
     */
    private static array $holidayCheckers = [];

    public function __construct(
        private readonly CarrierApiGatewayInterface $carrierApiGateway,
    ) {
    }

    public function getCollectionTimeSlots(
        string $countryCode,
        string $postalCode,
        Carbon $dateFrom,
        Carbon $dateTo,
        ?string $serviceCode = null,
    ): ResourcesInterface {
        if ($dateFrom->isAfter($dateTo)) {
            throw new InvalidInputException([
                new InvalidInputError('', 'Date from cannot be after date to'),
            ]);
        }

        $period = CarbonPeriod::create($dateFrom, $dateTo);
        $timeSlots = collect();

        foreach ($period as $date) {
            if ($date->isWeekend()) {
                continue;
            }

            if ($this->isHoliday($countryCode, $date)) {
                continue;
            }

            $from = $date->clone()->setTime(9, 0);
            $to = $date->clone()->setTime(18, 30);

            $timeSlots->add(
                new CollectionTimeSlot(
                    $from->toIso8601String() . '_' . $to->toIso8601String(),
                    $from,
                    $to,
                ),
            );
        }

        return new CollectionResources($timeSlots);

        // todo: If carrier API supports time availability checks, then use the CarrierApiGateway instead to get the time slots.

        // $queryParams = array_filter([
        //     'country_code' => $countryCode,
        //     'postal_code'  => $postalCode,
        //     'date_from'    => $dateFrom->toIso8601String(),
        //     'date_to'      => $dateTo->toIso8601String(),
        //     'service_code' => $serviceCode,
        // ]);

        // todo: Implement a request to the carrier with the CarrierApiGateway using the queryString.
        // $response = $this->carrierApiGateway->get('url', $queryParams);

        // todo: Map the result into CollectionTimeSlot resources.
        // return new PromiseResources(
        // // todo: Return the CollectionTimeSlots.
        // );
    }

    /**
     * @throws RuntimeException
     * @throws InvalidArgumentException
     * @throws UnknownLocaleException
     * @throws ProviderNotFoundException
     * @throws ReflectionException
     */
    private function isHoliday(string $countryCode, Carbon $date): bool
    {
        $countryCode = strtoupper($countryCode);
        $year = $date->year;

        if (!Arr::has(self::$holidayCheckers, $countryCode)) {
            self::$holidayCheckers[$countryCode] = [];
        }

        if (!Arr::has(self::$holidayCheckers[$countryCode], $year)) {
            self::$holidayCheckers[$countryCode][$year] = Yasumi::createByISO3166_2($countryCode, $year);
        }

        return !self::$holidayCheckers[$countryCode][$year]->isWorkingDay($date);
    }
}
