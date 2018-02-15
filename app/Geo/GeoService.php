<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Geo;

use DateInterval;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use MyParcelCom\Microservice\PickUpDropOffLocations\Position;
use Psr\Http\Message\ResponseInterface;
use Psr\SimpleCache\CacheInterface;

class GeoService
{
    protected const HEADER_SECRET = 'X-MYPARCELCOM-SECRET';
    protected const PATH_VALIDATE = '/validate';
    protected const PATH_SUGGEST = '/suggest';

    /** @var ClientInterface */
    protected $client;

    /** @var string */
    protected $secret;

    /** @var string */
    protected $baseUrl;

    /** @var CacheInterface */
    protected $cache;

    /**
     * @param string      $countryCode
     * @param string      $postalCode
     * @param null|string $street
     * @param null|int    $streetNumber
     * @param null|string $streetNumberSuffix
     * @return Position
     */
    public function getPositionForAddress(string $countryCode, string $postalCode, ?string $street = null, ?int $streetNumber = null, ?string $streetNumberSuffix = null): Position
    {
        $position = new Position();

        $cacheKey = implode('_', ['position', $countryCode, $postalCode, $street, $streetNumber, $streetNumberSuffix]);
        if (($address = $this->cache->get($cacheKey))) {
            return $this->mapJsonToPosition($address, $position);
        }

        $response = $this->doSuggestRequest($countryCode, $postalCode, $street, $streetNumber, $streetNumberSuffix);
        $json = \GuzzleHttp\json_decode((string)$response->getBody(), true);

        if (count($json) > 0) {
            $address = reset($json);
            $this->mapJsonToPosition($address, $position);

            // Cache for a week.
            $this->cache->set($cacheKey, new DateInterval('P1W'));
        }

        return $position;
    }

    /**
     * @param Position $sourcePosition
     * @param Position $destinationPosition
     * @return int Distance in meters.
     */
    public function getDistance(Position $sourcePosition, Position $destinationPosition): int
    {
        /**
         * @see https://www.movable-type.co.uk/scripts/latlong.html
         */
        $earthRadius = 6371e3;
        $lat1 = deg2rad($sourcePosition->getLatitude());
        $lat2 = deg2rad($destinationPosition->getLatitude());
        $latDiff = deg2rad($destinationPosition->getLatitude() - $sourcePosition->getLatitude());
        $longDiff = deg2rad($destinationPosition->getLongitude() - $sourcePosition->getLongitude());

        $a = sin($latDiff / 2) * sin($latDiff / 2)
            + cos($lat1) * cos($lat2)
            * sin($longDiff / 2) * sin($longDiff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = (int)round($earthRadius * $c);

        return $distance;
    }

    /**
     * @param null|string $countryCode
     * @param null|string $postalCode
     * @param null|string $street
     * @param int|null    $streetNumber
     * @param null|string $streetNumberSuffix
     * @return ResponseInterface
     */
    protected function doSuggestRequest(?string $countryCode = null, ?string $postalCode = null, ?string $street = null, ?int $streetNumber = null, ?string $streetNumberSuffix = null)
    {
        return $this->client->request(
            'post',
            $this->baseUrl . self::PATH_SUGGEST,
            [
                RequestOptions::HEADERS => [
                    self::HEADER_SECRET => $this->secret,
                ],
                RequestOptions::JSON    => array_filter([
                    'country'  => $countryCode,
                    'postcode' => $postalCode,
                    'street'   => $street,
                    'number'   => $streetNumber,
                    'addition' => $streetNumberSuffix,
                ]),
            ]
        );
    }

    /**
     * @param array    $address
     * @param Position $position
     * @return Position
     */
    protected function mapJsonToPosition(array $address, Position $position): Position
    {
        $position->setLongitude((float)$address['longitude']);
        $position->setLatitude((float)$address['latitude']);

        return $position;
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

    /**
     * @param string $secret
     * @return $this
     */
    public function setSecret(string $secret): self
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * @param string $baseUrl
     * @return $this
     */
    public function setBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * @param ClientInterface $client
     * @return $this
     */
    public function setClient(ClientInterface $client): self
    {
        $this->client = $client;

        return $this;
    }
}
