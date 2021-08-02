<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\PickUpDropOffLocations;

use DateInterval;
use Illuminate\Support\Collection;
use MyParcelCom\JsonApi\Resources\CollectionResources;
use MyParcelCom\JsonApi\Resources\Interfaces\ResourcesInterface;
use MyParcelCom\Microservice\Carrier\CarrierApiGatewayInterface;
use Psr\SimpleCache\CacheInterface;

class PickUpDropOffLocationRepository
{
    protected CarrierApiGatewayInterface $carrierApiGateway;
    protected CacheInterface $cache;

    public function __construct(CarrierApiGatewayInterface $carrierApiGateway, CacheInterface $cache)
    {
        $this->carrierApiGateway = $carrierApiGateway;
        $this->cache = $cache;
    }

    /**
     * @param string      $countryCode
     * @param string      $postalCode
     * @param string|null $street
     * @param string|null $streetNumber
     * @param string|null $city
     * @param array       $categories
     * @return ResourcesInterface
     */
    public function getAll(
        string $countryCode,
        string $postalCode,
        ?string $street = null,
        ?string $streetNumber = null,
        ?string $city = null,
        array $categories = []
    ): ResourcesInterface {
        // Return the locations if they are cached.
        if (($locations = $this->getCachedLocations($countryCode, $postalCode, $street, $streetNumber, $city))) {
            return new CollectionResources($this->filterLocationsByCategories($locations, $categories));
        }

        // TODO: Get the pudo points from carrier (use CarrierApiGateway).
        // TODO: Map data to PickUpDropOffLocation objects.
        // TODO: Put PickUpDropOffLocation objects in an object that implements ResourcesInterface.
        // TODO: Cache the collection of pudo locations using the method `setCachedLocations()`
        // TODO: Return pudo points filtered by passed categories using the method `filterLocationsByCategories()`
    }

    /**
     * @param Collection  $locations
     * @param string|null $countryCode
     * @param string|null $postalCode
     * @param string|null $street
     * @param string|null $streetNumber
     * @param string|null $city
     */
    protected function setCachedLocations(
        Collection $locations,
        ?string $countryCode,
        ?string $postalCode,
        ?string $street,
        ?string $streetNumber,
        ?string $city
    ): void {
        $key = $this->getCacheKey($countryCode, $postalCode, $street, $streetNumber, $city);

        $this->cache->set($key, $locations, new DateInterval('P1W'));
    }

    /**
     * Get the cached locations for given address. If no locations are cached return `null`.
     *
     * @param string|null $countryCode
     * @param string|null $postalCode
     * @param string|null $street
     * @param string|null $streetNumber
     * @param string|null $city
     * @return Collection|null
     */
    protected function getCachedLocations(
        ?string $countryCode,
        ?string $postalCode,
        ?string $street,
        ?string $streetNumber,
        ?string $city
    ): ?Collection {
        $key = $this->getCacheKey($countryCode, $postalCode, $street, $streetNumber, $city);

        return $this->cache->get($key);
    }

    /**
     * Return the cache key for the given address.
     *
     * @param string|null $countryCode
     * @param string|null $postalCode
     * @param string|null $street
     * @param string|null $streetNumber
     * @param string|null $city
     * @return string
     */
    protected function getCacheKey(
        ?string $countryCode,
        ?string $postalCode,
        ?string $street,
        ?string $streetNumber,
        ?string $city
    ): string {
        return implode('_', array_filter([
            'pudo_locations',
            $countryCode,
            $postalCode,
            $street,
            $streetNumber,
            $city,
        ]));
    }

    /**
     * @param Collection $locations
     * @param array      $categories
     * @return mixed
     */
    private function filterLocationsByCategories(Collection $locations, array $categories)
    {
        if (!$categories) {
            return $locations;
        }

        return $locations->filter(function (PickUpDropOffLocation $location) use ($categories) {
            foreach ($categories as $category) {
                if (in_array($category, $location->getCategories())) {
                    return true;
                }
            }
            return false;
        });
    }
}
