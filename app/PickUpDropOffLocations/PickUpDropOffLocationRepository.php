<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\PickUpDropOffLocations;

use DateInterval;
use Illuminate\Support\Collection;
use MyParcelCom\JsonApi\Resources\CollectionResources;
use MyParcelCom\JsonApi\Resources\Interfaces\ResourcesInterface;
use MyParcelCom\Microservice\Carrier\CarrierApiGatewayInterface;
use MyParcelCom\Microservice\Geo\GeoService;
use Psr\SimpleCache\CacheInterface;

class PickUpDropOffLocationRepository
{
    /** @var CarrierApiGatewayInterface */
    protected $carrierApiGateway;

    /** @var GeoService */
    protected $geoService;

    /** @var CacheInterface */
    protected $cache;

    /**
     * @param string      $countryCode
     * @param string      $postalCode
     * @param string|null $street
     * @param string|null $streetNumber
     * @return ResourcesInterface
     */
    public function getAll(string $countryCode, string $postalCode, ?string $street = null, ?string $streetNumber = null): ResourcesInterface
    {
        // Return the locations if they are cached.
        if (($locations = $this->getCachedLocations($countryCode, $postalCode, $street, $streetNumber))) {
            return new CollectionResources($locations);
        }

        // TODO: Get the pudo points from carrier (use CarrierApiGateway).
        // TODO: Map data to PickUpDropOffLocation objects.
        // TODO: Put PickUpDropOffLocation objects in an object that implements ResourcesInterface.
        // TODO: Use the method `updatePosition()` to update the position values of a pudo point with coordinates.
        // TODO: Cache the collection of pudo locations using the method `setCachedLocations()`
    }

    /**
     * @param PickUpDropOffLocation $location
     * @param null|string           $sourceCountryCode
     * @param null|string           $sourcePostalCode
     * @param null|string           $sourceStreet
     * @param int|null              $sourceStreetNumber
     * @return PickUpDropOffLocation
     */
    protected function updatePosition(PickUpDropOffLocation $location, ?string $sourceCountryCode, ?string $sourcePostalCode, ?string $sourceStreet, ?int $sourceStreetNumber)
    {
        $position = $location->getPosition() ?: new Position();

        if ($position->getLatitude() === null || $position->getLongitude() === null) {
            $address = $location->getAddress();
            $addressPosition = $this->geoService->getPositionForAddress(
                $address->getCountryCode(),
                $address->getPostalCode(),
                $address->getStreet1(),
                $address->getStreetNumber(),
                $address->getStreetNumberSuffix()
            );

            $position->setLatitude($addressPosition->getLatitude());
            $position->setLongitude($addressPosition->getLongitude());
        }

        if ($position->getDistance() === null) {
            $sourcePosition = $this->geoService->getPositionForAddress($sourceCountryCode, $sourcePostalCode, $sourceStreet, $sourceStreetNumber);

            $position->setDistance(
                $this->geoService->getDistance($sourcePosition, $position)
            );
        }

        return $location->setPosition($position);
    }

    /**
     * @param Collection  $locations
     * @param null|string $countryCode
     * @param null|string $postalCode
     * @param null|string $street
     * @param int|null    $streetNumber
     * @return void
     */
    protected function setCachedLocations(Collection $locations, ?string $countryCode, ?string $postalCode, ?string $street, ?int $streetNumber): void
    {
        $key = $this->getCacheKey($countryCode, $postalCode, $street, $streetNumber);

        // Cache for a week.
        $this->cache->set($key, $locations, new DateInterval("P1W"));
    }

    /**
     * Get the cached locations for given address. If no locations are cached
     * return `null`.
     *
     * @param null|string $countryCode
     * @param null|string $postalCode
     * @param null|string $street
     * @param int|null    $streetNumber
     * @return Collection|null
     */
    protected function getCachedLocations(?string $countryCode, ?string $postalCode, ?string $street, ?int $streetNumber): ?Collection
    {
        $key = $this->getCacheKey($countryCode, $postalCode, $street, $streetNumber);

        return $this->cache->get($key);
    }

    /**
     * Return the cache key for the given address.
     *
     * @param null|string $countryCode
     * @param null|string $postalCode
     * @param null|string $street
     * @param int|null    $streetNumber
     * @return string
     */
    protected function getCacheKey(?string $countryCode, ?string $postalCode, ?string $street, ?int $streetNumber): string
    {
        return implode(
            '_', [
                'pudo_locations',
                $countryCode,
                $postalCode,
                $street,
                $streetNumber,
            ]
        );
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
     * @param GeoService $geoService
     * @return $this
     */
    public function setGeoService(GeoService $geoService): self
    {
        $this->geoService = $geoService;

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
