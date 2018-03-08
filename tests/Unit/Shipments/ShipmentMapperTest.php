<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Shipments;

use Mockery;
use MyParcelCom\Microservice\PickUpDropOffLocations\Address;
use MyParcelCom\Microservice\Shipments\Customs;
use MyParcelCom\Microservice\Shipments\Option;
use MyParcelCom\Microservice\Shipments\PhysicalProperties;
use MyParcelCom\Microservice\Shipments\Service;
use MyParcelCom\Microservice\Shipments\Shipment;
use MyParcelCom\Microservice\Shipments\ShipmentItem;
use MyParcelCom\Microservice\Shipments\ShipmentMapper;
use PHPUnit\Framework\TestCase;

class ShipmentMapperTest extends TestCase
{
    protected function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }

    /** @test */
    public function testMap()
    {
        $mapper = new ShipmentMapper();

        $data = json_decode(file_get_contents(base_path('tests/Stubs/shipment-request.stub')), true);

        $physicalProperties = Mockery::mock(PhysicalProperties::class);
        $physicalProperties
            ->shouldReceive('setWeight')
            ->andReturnUsing(function ($weight) use ($physicalProperties) {
                $this->assertEquals(500, $weight);

                return $physicalProperties;
            })
            ->shouldReceive('setWidth')
            ->andReturnUsing(function ($width) use ($physicalProperties) {
                $this->assertEquals(200, $width);

                return $physicalProperties;
            })
            ->shouldReceive('setHeight')
            ->andReturnUsing(function ($height) use ($physicalProperties) {
                $this->assertEquals(100, $height);

                return $physicalProperties;
            })
            ->shouldReceive('setLength')
            ->andReturnUsing(function ($length) use ($physicalProperties) {
                $this->assertEquals(250, $length);

                return $physicalProperties;
            })
            ->shouldReceive('setVolume')
            ->andReturnUsing(function ($volume) use ($physicalProperties) {
                $this->assertEquals(5, $volume);

                return $physicalProperties;
            });

        $shipment = Mockery::mock(Shipment::class);
        $shipment
            ->shouldReceive('setDescription')
            ->andReturnUsing(function (string $description) use ($shipment) {
                $this->assertEquals('order #8008135', $description);

                return $shipment;
            })
            ->shouldReceive('setInsuranceCurrency')
            ->andReturnUsing(function (string $currency) use ($shipment) {
                $this->assertEquals('EUR', $currency);

                return $shipment;
            })
            ->shouldReceive('setInsuranceAmount')
            ->andReturnUsing(function (int $amount) use ($shipment) {
                $this->assertEquals(10000, $amount);

                return $shipment;
            })
            ->shouldReceive('getPhysicalProperties')
            ->andReturn($physicalProperties)
            ->shouldReceive('setService')
            ->andReturnUsing(function (Service $service) use ($shipment) {
                $this->assertEquals('service-a01', $service->getCode());
                $this->assertEquals('Parcel to Parcelshop', $service->getName());

                return $shipment;
            })
            ->shouldReceive('setSenderAddress')
            ->andReturnUsing(function (Address $address) use ($shipment) {
                $this->assertEquals('Acme Jewelry Co.', $address->getCompany());
                $this->assertEquals('1GL HF1', $address->getPostalCode());
                $this->assertEquals('john@doe.com', $address->getEmail());
                $this->assertNull($address->getPhoneNumber());
                $this->assertNull($address->getFirstName());
                $this->assertNull($address->getLastName());
                $this->assertEquals('GB', $address->getCountryCode());
                $this->assertEquals('Bamsterbam', $address->getCity());
                $this->assertNull($address->getRegionCode());
                $this->assertNull($address->getStreetNumberSuffix());
                $this->assertEquals(622, $address->getStreetNumber());
                $this->assertNull($address->getStreet2());
                $this->assertEquals('Streeterino', $address->getStreet1());

                return $shipment;
            })
            ->shouldReceive('setReturnAddress')
            ->andReturnUsing(function (Address $address) use ($shipment) {
                $this->assertEquals('Holmes Investigations', $address->getCompany());
                $this->assertEquals('NW1 6XE', $address->getPostalCode());
                $this->assertEquals('s.holmes@holmesinvestigations.com', $address->getEmail());
                $this->assertEquals('+31 234 567 890', $address->getPhoneNumber());
                $this->assertEquals('Sherlock', $address->getFirstName());
                $this->assertEquals('Holmes', $address->getLastName());
                $this->assertEquals('GB', $address->getCountryCode());
                $this->assertEquals('London', $address->getCity());
                $this->assertNull($address->getRegionCode());
                $this->assertNull($address->getStreetNumberSuffix());
                $this->assertEquals(221, $address->getStreetNumber());
                $this->assertNull($address->getStreet2());
                $this->assertEquals('Baker Street', $address->getStreet1());

                return $shipment;
            })
            ->shouldReceive('setRecipientAddress')
            ->andReturnUsing(function (Address $address) use ($shipment) {
                $this->assertNull($address->getCompany());
                $this->assertEquals('2131BC', $address->getPostalCode());
                $this->assertNull($address->getEmail());
                $this->assertEquals('+31 234 567 890', $address->getPhoneNumber());
                $this->assertEquals('John', $address->getFirstName());
                $this->assertEquals('Doe', $address->getLastName());
                $this->assertEquals('NL', $address->getCountryCode());
                $this->assertEquals('Amsterdam', $address->getCity());
                $this->assertNull($address->getRegionCode());
                $this->assertEquals('A', $address->getStreetNumberSuffix());
                $this->assertEquals(679, $address->getStreetNumber());
                $this->assertEquals('Room 3', $address->getStreet2());
                $this->assertEquals('Some road', $address->getStreet1());

                return $shipment;
            })
            ->shouldReceive('addOption')
            ->andReturnUsing(function ($option) use ($shipment) {
                $this->assertInstanceOf(Option::class, $option);
                /** @var Option $option */
                $this->assertEquals('delivery_day_sunday', $option->getCode());
                $this->assertEquals('Sunday delivery', $option->getName());

                return $shipment;
            })
            ->shouldReceive('getCustoms')
            ->andReturn(null)
            ->shouldReceive('setCustoms')
            ->andReturnUsing(function (Customs $customs) use ($shipment) {
                $this->assertEquals('gifts', $customs->getContentType());
                $this->assertEquals('876543', $customs->getInvoiceNumber());
                $this->assertEquals('DDU', $customs->getIncoterm());

                return $shipment;
            })
            ->shouldReceive('setItems')
            ->andReturnUsing(function (array $items) use ($shipment) {
                $this->assertInternalType('array', $items);
                array_walk($items, function (ShipmentItem $item) {
                    $this->assertInstanceOf(ShipmentItem::class, $item);
                    $this->assertNotNull($item->getSku());
                    $this->assertNotNull($item->getDescription());
                    $this->assertNotNull($item->getHsCode());
                    $this->assertInternalType('integer', $item->getItemValueAmount());
                    $this->assertNotNull($item->getItemValueAmount());
                    $this->assertNotNull($item->getItemValueCurrency());
                    $this->assertInternalType('integer', $item->getQuantity());
                    $this->assertNotNull($item->getQuantity());
                    $this->assertNotNull($item->getOriginCountryCode());
                });

                return $shipment;
            });

        $mapper->map($data['data'], $shipment);
    }
}
