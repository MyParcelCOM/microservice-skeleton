<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\PickUpDropOffLocations;

use Carbon\Carbon;
use Mockery;
use MyParcelCom\JsonApi\Interfaces\UrlGeneratorInterface;
use MyParcelCom\JsonApi\Transformers\TransformerException;
use MyParcelCom\JsonApi\Transformers\TransformerFactory;
use MyParcelCom\Microservice\PickUpDropOffLocations\Address;
use MyParcelCom\Microservice\PickUpDropOffLocations\OpeningHour;
use MyParcelCom\Microservice\PickUpDropOffLocations\PickUpDropOffLocation;
use MyParcelCom\Microservice\PickUpDropOffLocations\PickUpDropOffLocationTransformer;
use MyParcelCom\Microservice\PickUpDropOffLocations\Position;
use PHPUnit\Framework\TestCase;

class PickUpDropOffLocationTransformerTest extends TestCase
{
    /** @var PickUpDropOffLocationTransformer */
    private $pickUpDropOffLocationTransformer;

    /** @var PickUpDropOffLocation */
    private $pickUpDropOffLocation;

    public function setUp()
    {
        parent::setUp();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = Mockery::mock(UrlGeneratorInterface::class, ['route' => 'url']);
        /** @var TransformerFactory $transformerFactory */
        $transformerFactory = Mockery::mock(TransformerFactory::class);

        $this->pickUpDropOffLocationTransformer = (new PickUpDropOffLocationTransformer($transformerFactory))
            ->setUrlGenerator($urlGenerator);

        $address = Mockery::mock(Address::class, [
            'getStreet1'            => 'First Street',
            'getStreet2'            => 'Second Street',
            'getStreetNumber'       => 69,
            'getStreetNumberSuffix' => 'x',
            'getPostalCode'         => '1337OP',
            'getCity'               => 'Felicity',
            'getRegionCode'         => 'NH',
            'getCountryCode'        => 'NL',
            'getFirstName'          => 'Jane',
            'getLastName'           => 'Doe',
            'getCompany'            => 'Experts Exchange',
            'getEmail'              => 'john@expertsexchange.com',
            'getPhoneNumber'        => '1337-9001',
        ]);
        $position = Mockery::mock(Position::class, [
            'getLatitude'  => '48.8722379',
            'getLongitude' => '2.7736192',
            'getDistance'  => '528',
        ]);
        $openingHourA = Mockery::mock(OpeningHour::class, [
            'getDay'    => 'Monday',
            'getOpen'   => null,
            'getClosed' => null,
        ]);
        $openingHourB = Mockery::mock(OpeningHour::class, [
            'getDay'    => 'Friday',
            'getOpen'   => Carbon::parse('09:00'),
            'getClosed' => Carbon::parse('17:00'),
        ]);
        $this->pickUpDropOffLocation = Mockery::mock(PickUpDropOffLocation::class, [
            'getId'           => 'location-id',
            'getCategories'   => ['pick-up'],
            'getAddress'      => $address,
            'getPosition'     => $position,
            'getOpeningHours' => [$openingHourA, $openingHourB],
        ]);
    }

    public function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }

    public function testGetId()
    {
        $this->assertEquals(
            'location-id',
            $this->pickUpDropOffLocationTransformer->getId($this->pickUpDropOffLocation),
            'Failed getting model id.'
        );
    }

    public function testGetAttributes()
    {
        $this->assertEquals([
            'categories'    => ['pick-up'],
            'address'       => [
                'street_1'             => 'First Street',
                'street_2'             => 'Second Street',
                'street_number'        => '69',
                'street_number_suffix' => 'x',
                'postal_code'          => '1337OP',
                'city'                 => 'Felicity',
                'region_code'          => 'NH',
                'country_code'         => 'NL',
                'first_name'           => 'Jane',
                'last_name'            => 'Doe',
                'company'              => 'Experts Exchange',
                'email'                => 'john@expertsexchange.com',
                'phone_number'         => '1337-9001',
            ],
            'opening_hours' => [
                [
                    'day'    => 'Monday',
                    'open'   => '00:00',
                    'closed' => '00:00',
                ],
                [
                    'day'    => 'Friday',
                    'open'   => '09:00',
                    'closed' => '17:00',
                ],
            ],
            'position'      => [
                'latitude'  => '48.8722379',
                'longitude' => '2.7736192',
                'distance'  => '528',
            ],
        ], $this->pickUpDropOffLocationTransformer->getAttributes($this->pickUpDropOffLocation));
    }

    public function testGetIdWithInvalidModel()
    {
        $this->expectException(TransformerException::class);
        $this->pickUpDropOffLocationTransformer->getId(Mockery::mock(\stdClass::class));
    }

    public function testGetAttributesWithInvalidModel()
    {
        $this->expectException(TransformerException::class);
        $this->pickUpDropOffLocationTransformer->getAttributes(Mockery::mock(\stdClass::class));
    }
}
