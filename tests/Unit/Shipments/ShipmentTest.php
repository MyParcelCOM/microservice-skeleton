<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Shipments;

use Mockery;
use MyParcelCom\Microservice\PickUpDropOffLocations\Address;
use MyParcelCom\Microservice\Shipments\File;
use MyParcelCom\Microservice\Shipments\Option;
use MyParcelCom\Microservice\Shipments\PhysicalProperties;
use MyParcelCom\Microservice\Shipments\Service;
use MyParcelCom\Microservice\Shipments\Shipment;
use PHPUnit\Framework\TestCase;

class ShipmentTest extends TestCase
{
    protected function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }

    /** @test */
    public function testId()
    {
        $shipment = new Shipment();
        $this->assertEquals('id-yourself', $shipment->setId('id-yourself')->getId());
    }

    /** @test */
    public function testRecipientAddress()
    {
        $shipment = new Shipment();
        $address = Mockery::mock(Address::class);
        $this->assertEquals($address, $shipment->setRecipientAddress($address)->getRecipientAddress());
    }

    /** @test */
    public function testSenderAddress()
    {
        $shipment = new Shipment();
        $address = Mockery::mock(Address::class);
        $this->assertEquals($address, $shipment->setSenderAddress($address)->getSenderAddress());
    }

    /** @test */
    public function testPickupLocationCode()
    {
        $shipment = new Shipment();
        $this->assertEquals('A124', $shipment->setPickupLocationCode('A124')->getPickupLocationCode());
    }

    /** @test */
    public function testPickupLocationAddress()
    {
        $shipment = new Shipment();
        $address = Mockery::mock(Address::class);
        $this->assertEquals($address, $shipment->setPickupLocationAddress($address)->getPickupLocationAddress());
    }

    /** @test */
    public function testDescription()
    {
        $shipment = new Shipment();
        $this->assertEquals('Some sort of description', $shipment->setDescription('Some sort of description')->getDescription());
    }

    /** @test */
    public function testPriceAmount()
    {
        $shipment = new Shipment();
        $this->assertEquals(1337, $shipment->setPriceAmount(1337)->getPriceAmount());
    }

    /** @test */
    public function testPriceCurrency()
    {
        $shipment = new Shipment();
        $this->assertEquals('USD', $shipment->setPriceCurrency('USD')->getPriceCurrency());
    }

    /** @test */
    public function testInsuranceAmount()
    {
        $shipment = new Shipment();
        $this->assertEquals(9001, $shipment->setInsuranceAmount(9001)->getInsuranceAmount());
    }

    /** @test */
    public function testInsuranceCurrency()
    {
        $shipment = new Shipment();
        $this->assertEquals('EUR', $shipment->setInsuranceCurrency('EUR')->getInsuranceCurrency());
    }

    /** @test */
    public function testBarcode()
    {
        $shipment = new Shipment();
        $this->assertEquals('S3JEWEETWEL', $shipment->setBarcode('S3JEWEETWEL')->getBarcode());
    }

    /** @test */
    public function testWeight()
    {
        $shipment = new Shipment();
        $this->assertEquals(69, $shipment->setWeight(69)->getWeight());
    }

    /** @test */
    public function testService()
    {
        $shipment = new Shipment();
        $service = Mockery::mock(Service::class);
        $this->assertEquals($service, $shipment->setService($service)->getService());
    }

    /** @test */
    public function testOptions()
    {
        $shipment = new Shipment();
        $options = [
            Mockery::mock(Option::class),
            Mockery::mock(Option::class),
        ];
        $this->assertEquals($options, $shipment->setOptions($options)->getOptions());

        $option = Mockery::mock(Option::class);
        $options[] = $option;
        $this->assertEquals($options, $shipment->addOption($option)->getOptions());
    }

    /** @test */
    public function testPhysicalProperties()
    {
        $shipment = new Shipment();
        $physicalProperties = Mockery::mock(PhysicalProperties::class);
        $this->assertEquals($physicalProperties, $shipment->setPhysicalProperties($physicalProperties)->getPhysicalProperties());
    }

    /** @test */
    public function testFiles()
    {
        $shipment = new Shipment();
        $file = Mockery::mock(File::class);
        $files = [$file];
        $this->assertEquals($files, $shipment->addFile($file)->getFiles());
    }
}
