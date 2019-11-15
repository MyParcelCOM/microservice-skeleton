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
    /** @var CarrierApiGatewayInterface */
    protected $carrierApiGateway;

    /** @var CacheInterface */
    protected $cache;

    /**
     * @param string      $countryCode
     * @param string      $postalCode
     * @param string|null $street
     * @param string|null $streetNumber
     * @param array       $categories
     * @return ResourcesInterface
     */
    public function getAll(
        string $countryCode,
        string $postalCode,
        ?string $street = null,
        ?string $streetNumber = null,
        array $categories = []
    ): ResourcesInterface {
        // Return the locations if they are cached.
        if (($locations = $this->getCachedLocations($countryCode, $postalCode, $street, $streetNumber))) {
            return new CollectionResources($locations);
        }

        // TODO: Get the pudo points from carrier (use CarrierApiGateway).
        // TODO: Filter pudo points by passed categories.
        // TODO: Map data to PickUpDropOffLocation objects.
        // TODO: Put PickUpDropOffLocation objects in an object that implements ResourcesInterface.
        // TODO: Cache the collection of pudo locations using the method `setCachedLocations()`
    }

    /**
     * @param Collection  $locations
     * @param string|null $countryCode
     * @param string|null $postalCode
     * @param string|null $street
     * @param int|null    $streetNumber
     */
    protected function setCachedLocations(
        Collection $locations,
        ?string $countryCode,
        ?string $postalCode,
        ?string $street,
        ?int $streetNumber
    ): void {
        $key = $this->getCacheKey($countryCode, $postalCode, $street, $streetNumber);

        $this->cache->set($key, $locations, new DateInterval('P1W'));
    }

    /**
     * Get the cached locations for given address. If no locations are cached return `null`.
     *
     * @param string|null $countryCode
     * @param string|null $postalCode
     * @param string|null $street
     * @param int|null    $streetNumber
     * @return Collection|null
     */
    protected function getCachedLocations(
        ?string $countryCode,
        ?string $postalCode,
        ?string $street,
        ?int $streetNumber
    ): ?Collection {
        $key = $this->getCacheKey($countryCode, $postalCode, $street, $streetNumber);

        return $this->cache->get($key);
    }

    /**
     * Return the cache key for the given address.
     *
     * @param string|null $countryCode
     * @param string|null $postalCode
     * @param string|null $street
     * @param int|null    $streetNumber
     * @return string
     */
    protected function getCacheKey(?string $countryCode, ?string $postalCode, ?string $street, ?int $streetNumber): string
    {
        return implode('_', [
            'pudo_locations',
            $countryCode,
            $postalCode,
            $street,
            $streetNumber,
        ]);
    }

    /**
     * @param CarrierApiGatewayInterface $gateway
     * @return $this
     */
    public function setCarrierApiGateway(CarrierApiGatewayInterface $gateway): self
    {
        $this->carrierApiGateway = $gateway;

        return $this;
    }

    /**
     * @param CacheInterface $cache
     * @return $this
     */
    public function setCache(CacheInterface $cache): self
    {
        $this->cache = $cache;

        return $this;
    }
}
