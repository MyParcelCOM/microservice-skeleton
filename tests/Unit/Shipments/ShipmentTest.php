<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Shipments;

use MyParcelCom\Microservice\Enums\TaxTypeEnum;
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

    /** @test */
    public function testSenderTaxNumber()
    {
        $this->assertEquals('74X', $this->shipment->setSenderTaxNumber('74X')->getSenderTaxNumber());

        $this->assertEquals('74X', $this->shipment->getSenderTaxIdentificationNumber(TaxTypeEnum::EORI()));
        $this->assertEquals('74X', $this->shipment->getSenderTaxIdentificationNumber(TaxTypeEnum::EORI(), '74'));
        $this->assertNull($this->shipment->getSenderTaxIdentificationNumber(TaxTypeEnum::EORI(), 'GB'));
        $this->assertNull($this->shipment->getSenderTaxIdentificationNumber(TaxTypeEnum::IOSS()));
    }

    /** @test */
    public function testSenderTaxIdentificationNumbers()
    {
        $taxIdentificationNumbers = [
            [
                'country_code' => 'GB',
                'number'       => 'XI123456789',
                'type'         => 'eori',
            ],
        ];
        $this->assertEquals([], $this->shipment->getSenderTaxIdentificationNumbers());
        $this->assertEquals($taxIdentificationNumbers, $this->shipment->setSenderTaxIdentificationNumbers($taxIdentificationNumbers)->getSenderTaxIdentificationNumbers());
    }

    /** @test */
    public function testSenderTaxIdentificationNumber()
    {
        $this->shipment->setSenderTaxIdentificationNumbers([
            [
                'country_code' => 'GB',
                'number'       => 'XI123456789',
                'type'         => 'eori',
            ],
        ]);
        $this->assertEquals('XI123456789', $this->shipment->getSenderTaxIdentificationNumber(TaxTypeEnum::EORI(), ...['GB', 'NL']));
        $this->assertEquals('XI123456789', $this->shipment->getSenderTaxIdentificationNumber(TaxTypeEnum::EORI(), 'GB'));
        $this->assertEquals('XI123456789', $this->shipment->getSenderTaxIdentificationNumber(TaxTypeEnum::EORI()));
        $this->assertNull($this->shipment->getSenderTaxIdentificationNumber(TaxTypeEnum::EORI(), 'NL'));
        $this->assertNull($this->shipment->getSenderTaxIdentificationNumber(TaxTypeEnum::IOSS()));
    }

    /** @test */
    public function testRecipientTaxNumber()
    {
        $this->assertEquals('74X', $this->shipment->setRecipientTaxNumber('74X')->getRecipientTaxNumber());

        $this->assertEquals('74X', $this->shipment->getRecipientTaxIdentificationNumber(TaxTypeEnum::EORI()));
        $this->assertEquals('74X', $this->shipment->getRecipientTaxIdentificationNumber(TaxTypeEnum::EORI(), '74'));
        $this->assertNull($this->shipment->getRecipientTaxIdentificationNumber(TaxTypeEnum::EORI(), 'GB'));
        $this->assertNull($this->shipment->getRecipientTaxIdentificationNumber(TaxTypeEnum::IOSS()));
    }

    /** @test */
    public function testRecipientTaxIdentificationNumbers()
    {
        $taxIdentificationNumbers = [
            [
                'country_code' => 'GB',
                'number'       => 'XI123456789',
                'type'         => 'eori',
            ],
        ];
        $this->assertEquals([], $this->shipment->getRecipientTaxIdentificationNumbers());
        $this->assertEquals($taxIdentificationNumbers, $this->shipment->setRecipientTaxIdentificationNumbers($taxIdentificationNumbers)->getRecipientTaxIdentificationNumbers());
    }

    /** @test */
    public function testRecipientTaxIdentificationNumber()
    {
        $this->shipment->setRecipientTaxIdentificationNumbers([
            [
                'country_code' => 'GB',
                'number'       => 'XI123456789',
                'type'         => 'eori',
            ],
        ]);
        $this->assertEquals('XI123456789', $this->shipment->getRecipientTaxIdentificationNumber(TaxTypeEnum::EORI(), ...['GB', 'NL']));
        $this->assertEquals('XI123456789', $this->shipment->getRecipientTaxIdentificationNumber(TaxTypeEnum::EORI(), 'GB'));
        $this->assertEquals('XI123456789', $this->shipment->getRecipientTaxIdentificationNumber(TaxTypeEnum::EORI()));
        $this->assertNull($this->shipment->getRecipientTaxIdentificationNumber(TaxTypeEnum::EORI(), 'NL'));
        $this->assertNull($this->shipment->getRecipientTaxIdentificationNumber(TaxTypeEnum::IOSS()));
    }

    /** @test */
    public function testTotalValue()
    {
        $this->assertNull($this->shipment->getTotalValue());

        $this->assertEquals(42, $this->shipment->setTotalValueAmount(42)->getTotalValueAmount());
        $this->assertEquals('EUR', $this->shipment->setTotalValueCurrency('EUR')->getTotalValueCurrency());

        $this->assertEquals([
            'amount'   => 42,
            'currency' => 'EUR',
        ], $this->shipment->getTotalValue());
    }

    /** @test */
    public function testTotalValueBasedOnItems()
    {
        $this->assertNull($this->shipment->getTotalValue());

        $this->shipment->setItems([
            (new ShipmentItem())->setQuantity(2)->setItemValueAmount(4)->setItemValueCurrency('FOO'),
            (new ShipmentItem())->setQuantity(3)->setItemValueAmount(5)->setItemValueCurrency('BAR'),
        ]);

        $this->assertEquals([
            'amount'   => 23,
            'currency' => 'BAR',
        ], $this->shipment->getTotalValue());
    }

    public function testTaxIdentificationNumbers()
    {
        $this->assertEquals([], $this->shipment->getTaxIdentificationNumbers());

        $taxIdentificationNumber = [
            'country_code' => 'GB',
            'number'       => 'XI123456789',
            'type'         => 'eori',
        ];

        $this->shipment->setTaxIdentificationNumbers([$taxIdentificationNumber]);

        $this->assertEquals([$taxIdentificationNumber], $this->shipment->getTaxIdentificationNumbers());
        $this->assertEquals('XI123456789', $this->shipment->getTaxIdentificationNumber('eori', 'GB'));
        $this->assertNull($this->shipment->getTaxIdentificationNumber('eori', 'GG'));
    }
}
