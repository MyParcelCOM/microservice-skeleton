<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Shipments;

use Mockery;
use MyParcelCom\Microservice\PickUpDropOffLocations\Address;
use MyParcelCom\Microservice\Shipments\Customs;
use MyParcelCom\Microservice\Shipments\CustomsItem;
use MyParcelCom\Microservice\Shipments\Service;
use MyParcelCom\Microservice\Shipments\Shipment;
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

        $data = json_decode(file_get_contents(base_path('tests/Stubs/shipment-request.json')), true);

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
            ->shouldReceive('setWeight')
            ->andReturnUsing(function (int $weight) use ($shipment) {
                $this->assertEquals(24, $weight);

                return $shipment;
            })
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
            ->shouldReceive('getCustoms')
            ->andReturn(null)
            ->shouldReceive('setCustoms')
            ->andReturnUsing(function (Customs $customs) use ($shipment) {
                $this->assertEquals('gifts', $customs->getContentType());
                $this->assertEquals('876543', $customs->getInvoiceNumber());
                $this->assertEquals('DDU', $customs->getIncoterm());

                $items = array_map(function (CustomsItem $item) {
                    return [
                        'sku'               => $item->getSku(),
                        'description'       => $item->getDescription(),
                        'hsCode'            => $item->getHsCode(),
                        'quantity'          => $item->getQuantity(),
                        'itemValueAmount'   => $item->getItemValueAmount(),
                        'itemValueCurrency' => $item->getItemValueCurrency(),
                        'originCountryCode' => $item->getOriginCountryCode(),
                    ];
                }, $customs->getItems());

                $this->assertEquals([
                    [
                        'sku'               => '13657za',
                        'description'       => 'XBox 360',
                        'hsCode'            => '1234.15.05',
                        'quantity'          => 2,
                        'itemValueAmount'   => 30000,
                        'itemValueCurrency' => 'EUR',
                        'originCountryCode' => 'GB',
                    ],
                    [
                        'sku'               => '654324re',
                        'description'       => 'Playstation 2',
                        'hsCode'            => '1234.15.05',
                        'quantity'          => 1,
                        'itemValueAmount'   => 20000,
                        'itemValueCurrency' => 'EUR',
                        'originCountryCode' => 'GB',
                    ],
                ], $items);

                return $shipment;
            });

        $mapper->map($data['data'], $shipment);
    }
}
