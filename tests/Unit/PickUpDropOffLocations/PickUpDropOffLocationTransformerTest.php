<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\PickUpDropOffLocations;

use Carbon\Carbon;
use Illuminate\Routing\UrlGenerator;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use MyParcelCom\JsonApi\Exceptions\ModelTypeException;
use MyParcelCom\JsonApi\Transformers\TransformerFactory;
use MyParcelCom\Microservice\PickUpDropOffLocations\Address;
use MyParcelCom\Microservice\PickUpDropOffLocations\OpeningHour;
use MyParcelCom\Microservice\PickUpDropOffLocations\PickUpDropOffLocation;
use MyParcelCom\Microservice\PickUpDropOffLocations\PickUpDropOffLocationTransformer;
use MyParcelCom\Microservice\PickUpDropOffLocations\Position;
use PHPUnit\Framework\TestCase;
use stdClass;

class PickUpDropOffLocationTransformerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var PickUpDropOffLocationTransformer */
    private $pickUpDropOffLocationTransformer;

    /** @var PickUpDropOffLocation */
    private $pickUpDropOffLocation;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var TransformerFactory $transformerFactory */
        $transformerFactory = Mockery::mock(TransformerFactory::class);

        $this->pickUpDropOffLocationTransformer = (new PickUpDropOffLocationTransformer($transformerFactory))
            ->setUrlGenerator(Mockery::mock(UrlGenerator::class, ['route' => 'url']));

        $address = Mockery::mock(Address::class, [
            'getStreet1'            => 'First Street',
            'getStreet2'            => 'Second Street',
            'getStreetNumber'       => 69,
            'getStreetNumberSuffix' => 'x',
            'getPostalCode'         => '1337OP',
            'getCity'               => 'Felicity',
            'getRegionCode'         => 'NH',
            'getStateCode'          => 'XXX',
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
            'getFeatures'     => ['print-label-in-store'],
            'getAddress'      => $address,
            'getOpeningHours' => [$openingHourA, $openingHourB],
            'getPosition'     => $position,
            'getDistance'     => '528',
            'getLocationType' => 'office',
        ]);
    }

    public function testGetId()
    {
        $this->assertEquals(
            'location-id',
            $this->pickUpDropOffLocationTransformer->getId($this->pickUpDropOffLocation),
            'Failed getting model id.',
        );
    }

    public function testGetAttributes()
    {
        $this->assertEquals([
            'categories'    => ['pick-up'],
            'features'      => ['print-label-in-store'],
            'location_type' => 'office',
            'address'       => [
                'street_1'             => 'First Street',
                'street_2'             => 'Second Street',
                'street_number'        => '69',
                'street_number_suffix' => 'x',
                'postal_code'          => '1337OP',
                'city'                 => 'Felicity',
                'region_code'          => 'NH',
                'state_code'           => 'XXX',
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
            ],
        ], $this->pickUpDropOffLocationTransformer->getAttributes($this->pickUpDropOffLocation));
    }

    public function testGetMeta()
    {
        $this->assertEquals([
            'distance' => '528',
        ], $this->pickUpDropOffLocationTransformer->getMeta($this->pickUpDropOffLocation));
    }

    public function testGetIdWithInvalidModel()
    {
        $this->expectException(ModelTypeException::class);
        $this->pickUpDropOffLocationTransformer->getId(new stdClass());
    }

    public function testGetAttributesWithInvalidModel()
    {
        $this->expectException(ModelTypeException::class);
        $this->pickUpDropOffLocationTransformer->getAttributes(new stdClass());
    }
}
