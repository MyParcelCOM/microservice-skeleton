<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Address;

use GuzzleHttp\Client;
use Mockery;
use MyParcelCom\Microservice\Geo\GeoService;
use MyParcelCom\Microservice\PickUpDropOffLocations\Position;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\SimpleCache\CacheInterface;
use function GuzzleHttp\Promise\promise_for;

class GeoServiceTest extends TestCase
{
    /** @var GeoService */
    private $geoService;

    protected function setUp()
    {
        parent::setUp();

        $response = Mockery::mock(ResponseInterface::class, [
            'getBody' => json_encode(
                [
                    [
                        'building'  => '',
                        'country'   => 'United Kingdom',
                        'dst'       => '+01:00\r',
                        'elevation' => '111',
                        'fips'      => 'UKP8',
                        'hasc'      => 'GB.WL',
                        'id'        => 2027542256,
                        'iso'       => 'GB',
                        'iso2'      => 'GB-WIL',
                        'language'  => 'EN',
                        'latitude'  => '51.429631',
                        'locality'  => 'Corsham',
                        'longitude' => '-2.201486',
                        'nuts'      => 'UKK15',
                        'postcode'  => 'SN13 9UB',
                        'range'     => '1 - 55',
                        'region1'   => 'England',
                        'region2'   => 'South West England',
                        'region3'   => 'Wiltshire',
                        'region4'   => '',
                        'stat'      => 'E06000054',
                        'street'    => 'Home Mead',
                        'suburb'    => '',
                        'timezone'  => 'Europe/London',
                        'utc'       => '+00:00',
                    ],
                ]
            ),
        ]);

        $client = Mockery::mock(Client::class, [
            'requestAsync' => promise_for($response),
            'request'      => $response,
        ]);

        $cache = Mockery::mock(CacheInterface::class, ['set' => true]);
        $cache->shouldReceive('get')->andReturnUsing(function ($key, $default = null) {
            return $default;
        });

        $this->geoService = (new GeoService())
            ->setBaseUrl('https://address.myparcel.com')
            ->setSecret('some-crazy-secret')
            ->setCache($cache)
            ->setClient($client);
    }

    protected function tearDown()
    {
        Mockery::close();

        parent::tearDown();
    }

    /** @test */
    public function testGetPosition()
    {
        $position = $this->geoService->getPositionForAddress('GB', 'SN13 9UB', 'Home Mead');

        $this->assertEquals(51.429631, $position->getLatitude());
        $this->assertEquals(-2.201486, $position->getLongitude());
    }

    /** @test */
    public function testGetDistance()
    {
        // Hoofddorp station
        $source = (new Position())->setLatitude(52.292629)->setLongitude(4.698387);
        // Mayor's Mansion
        $destination = (new Position())->setLatitude(52.304860)->setLongitude(4.691103);

        $distance = $this->geoService->getDistance($source, $destination);

        // Expected value as calculated by https://www.movable-type.co.uk/scripts/latlong.html
        $this->assertEquals(1447, $distance);
    }
}
