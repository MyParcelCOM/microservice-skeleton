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
     * @param array       $features
     * @return ResourcesInterface
     */
    public function getAllByCountryAndPostalCode(
        string $countryCode,
        string $postalCode,
        ?string $street = null,
        ?string $streetNumber = null,
        ?string $city = null,
        array $categories = [],
        array $features = []
    ): ResourcesInterface {
        // Return the locations if they are cached.
        if (($locations = $this->getCachedLocations($countryCode, $postalCode, $street, $streetNumber, $city))) {
            return new CollectionResources(
                $this->filterLocationsByCategories(
                    $this->filterLocationsByFeatures(
                        $locations,
                        $features
                    ),
                    $categories
                )
            );
        }

        // TODO: Get the pudo points from carrier (use CarrierApiGateway).
        // TODO: Map data to PickUpDropOffLocation objects.
        // TODO: Put PickUpDropOffLocation objects in an object that implements ResourcesInterface.
        // TODO: Cache the collection of pudo locations using the method `setCachedLocations()`
        // TODO: Return pudo points filtered by passed categories using the method `filterLocationsByCategories()`
    }

    /**
     * @param string   $latitude
     * @param string   $longitude
     * @param int|null $radius Radius is in meters
     * @param array    $categories
     * @param array    $features
     * @return ResourcesInterface
     */
    public function getAllByGeolocation(
        string $latitude,
        string $longitude,
        ?int $radius = null,
        array $categories = [],
        array $features = []
    ): ResourcesInterface {
        return new CollectionResources(
            $this->filterLocationsByCategories(
                $this->filterLocationsByFeatures(
                    new Collection(),
                    $features
                ),
                $categories
            )
        );

        // TODO: Get the pudo points from carrier (use CarrierApiGateway).
        // TODO: Map data to PickUpDropOffLocation objects.
        // TODO: Put PickUpDropOffLocation objects in an object that implements ResourcesInterface.
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
     * @return Collection
     */
    private function filterLocationsByCategories(Collection $locations, array $categories): Collection
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

    /**
     * @param Collection $locations
     * @param array $features
     * @return Collection
     */
    private function filterLocationsByFeatures(Collection $locations, array $features): Collection
    {
        if (!$features) {
            return $locations;
        }

        return $locations->filter(function (PickUpDropOffLocation $location) use ($features) {
            foreach ($features as $feature) {
                if (in_array($feature, $location->getFeatures())) {
                    return true;
                }
            }
            return false;
        });
    }
}
