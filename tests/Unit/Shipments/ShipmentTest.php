<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Shipments;

use MyParcelCom\Microservice\PickUpDropOffLocations\Address;
use MyParcelCom\Microservice\Shipments\Customs;
use MyParcelCom\Microservice\Shipments\File;
use MyParcelCom\Microservice\Shipments\Option;
use MyParcelCom\Microservice\Shipments\PhysicalProperties;
use MyParcelCom\Microservice\Shipments\Service;
use MyParcelCom\Microservice\Shipments\Shipment;
use MyParcelCom\Microservice\Shipments\ShipmentItem;
use PHPUnit\Framework\TestCase;

class ShipmentTest extends TestCase
{
    /** @var Shipment */
    protected $shipment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->shipment = new Shipment();
    }

    /** @test */
    public function testId()
    {
        $this->assertEquals('id-yourself', $this->shipment->setId('id-yourself')->getId());
    }

    /** @test */
    public function testRecipientAddress()
    {
        $address = new Address();
        $this->assertEquals($address, $this->shipment->setRecipientAddress($address)->getRecipientAddress());
    }

    /** @test */
    public function testReturnAddress()
    {
        $address = new Address();
        $this->assertEquals($address, $this->shipment->setReturnAddress($address)->getReturnAddress());
    }

    /** @test */
    public function testSenderAddress()
    {
        $address = new Address();
        $this->assertEquals($address, $this->shipment->setSenderAddress($address)->getSenderAddress());
    }

    /** @test */
    public function testPickupLocationCode()
    {
        $this->assertEquals('A124', $this->shipment->setPickupLocationCode('A124')->getPickupLocationCode());
    }

    /** @test */
    public function testPickupLocationAddress()
    {
        $address = new Address();
        $this->assertEquals($address, $this->shipment->setPickupLocationAddress($address)->getPickupLocationAddress());
    }

    /** @test */
    public function testDescription()
    {
        $this->assertEquals('Some sort of', $this->shipment->setDescription('Some sort of')->getDescription());
    }

    /** @test */
    public function testBarcode()
    {
        $this->assertEquals('S3JEWEETWEL', $this->shipment->setBarcode('S3JEWEETWEL')->getBarcode());
    }

    /** @test */
    public function testService()
    {
        $service = new Service();
        $this->assertEquals($service, $this->shipment->setService($service)->getService());
    }

    /** @test */
    public function testOptions()
    {
        $options = [
            new Option(),
            new Option(),
        ];
        $this->assertEquals($options, $this->shipment->setOptions($options)->getOptions());

        $option = new Option();
        $options[] = $option;
        $this->assertEquals($options, $this->shipment->addOption($option)->getOptions());
    }

    /** @test */
    public function testPhysicalProperties()
    {
        $physicalProperties = new PhysicalProperties();
        $this->assertEquals($physicalProperties, $this->shipment->setPhysicalProperties($physicalProperties)->getPhysicalProperties());
    }

    /** @test */
    public function testFiles()
    {
        $file = new File();
        $files = [$file];
        $this->assertEquals($files, $this->shipment->addFile($file)->getFiles());
    }

    /** @test */
    public function testCustoms()
    {
        $customs = new Customs();
        $this->assertEquals($customs, $this->shipment->setCustoms($customs)->getCustoms());
    }

    /** @test */
    public function testItems()
    {
        $this->assertEmpty($this->shipment->getItems());

        $items = [
            new ShipmentItem(),
            new ShipmentItem(),
        ];
        $this->shipment->setItems($items);
        $this->assertEquals($items, $this->shipment->getItems());

        $item = new ShipmentItem();
        $items[] = $item;
        $this->shipment->addItem($item);
        $this->assertEquals($items, $this->shipment->getItems());
    }

    /** @test */
    public function testTrackTraceEnabled()
    {
        $this->assertTrue($this->shipment->isTrackTraceEnabled());
        $this->assertFalse($this->shipment->setTrackTraceEnabled(false)->isTrackTraceEnabled());
    }

    /** @test */
    public function testItSetsMyparcelcomShipmentId()
    {
        $id = 'bbacd0c7-9ec5-42df-9870-443b8e1a7155';

        $this->assertEquals($id, $this->shipment->setMyparcelcomShipmentId($id)->getMyparcelcomShipmentId());
    }

    /** @test */
    public function testItSetsAndGetsLabelMimeType()
    {
        $this->assertEquals(Shipment::LABEL_MIME_TYPE_PDF, $this->shipment->getLabelMimeType());

        $this->shipment->setLabelMimeType(Shipment::LABEL_MIME_TYPE_ZPL);
        $this->assertEquals(Shipment::LABEL_MIME_TYPE_ZPL, $this->shipment->getLabelMimeType());
    }

    /** @test */
    public function testItSetsAndGetsChannel()
    {
        $this->shipment->setChannel('eBay');
        $this->assertEquals('eBay', $this->shipment->getChannel());
    }
}
