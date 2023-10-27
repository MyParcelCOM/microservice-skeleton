<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\PickUpDropOffLocations;

use DateInterval;
use Illuminate\Support\Collection;
use MyParcelCom\JsonApi\Resources\CollectionResources;
use MyParcelCom\JsonApi\Resources\Interfaces\ResourcesInterface;
use MyParcelCom\JsonApi\Resources\PromiseResources;
use MyParcelCom\Microservice\Carrier\CarrierApiGatewayInterface;
use Psr\SimpleCache\CacheInterface;

class PickUpDropOffLocationRepository
{
    public function __construct(
        private readonly CarrierApiGatewayInterface $carrierApiGateway,
        private readonly CacheInterface $cache,
    ) {
    }

    public function getAllByCountryAndPostalCode(
        string $countryCode,
        string $postalCode,
        ?string $street = null,
        ?string $streetNumber = null,
        ?string $city = null,
        array $categories = [],
        array $features = [],
        array $locationTypes = []
    ): ResourcesInterface {
        // Return the locations if they are cached.
        if (($locations = $this->getCachedLocations($countryCode, $postalCode, $street, $streetNumber, $city))) {
            return new CollectionResources(
                $this->filterLocationsByCategories(
                    $this->filterLocationsByFeatures(
                        $this->filterLocationsByLocationType(
                            $locations,
                            $locationTypes
                        ),
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

    public function getAllByGeolocation(
        string $latitude,
        string $longitude,
        ?int $radius = null,
        array $categories = [],
        array $features = [],
        array $locationTypes = []
    ): ResourcesInterface {
        return new CollectionResources(
            $this->filterLocationsByCategories(
                $this->filterLocationsByFeatures(
                    $this->filterLocationsByLocationType(
                        new Collection(),
                        $locationTypes
                    ),
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

    public function getById(string $pickUpDropOffLocationId): ?PickUpDropOffLocation
    {
        // TODO: Get the pudo point from carrier (use CarrierApiGateway).
        // TODO: Map data to PickUpDropOffLocation object.
        // TODO: Return PickUpDropOffLocation object in an object that implements ResourcesInterface.

        return null;
    }

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

    private function filterLocationsByLocationType(Collection $locations, array $locationTypes): Collection
    {
        if (!$locationTypes) {
            return $locations;
        }

        return $locations->filter(function (PickUpDropOffLocation $location) use ($locationTypes) {
            foreach ($locationTypes as $type) {
                if ($location->getLocationType() === $type) {
                    return true;
                }
            }

            return false;
        });
    }
}
