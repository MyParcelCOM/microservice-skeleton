<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Shipments;

use Mockery;
use MyParcelCom\Microservice\Shipments\Customs;
use MyParcelCom\Microservice\Shipments\CustomsItem;
use PHPUnit\Framework\TestCase;

class CustomsTest extends TestCase
{
    protected function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }

    /** @test */
    public function testContentType()
    {
        $customs = new Customs();
        $this->assertEquals(
            Customs::CONTENT_TYPE_SAMPLE_MERCHANDISE,
            $customs->setContentType(Customs::CONTENT_TYPE_SAMPLE_MERCHANDISE)->getContentType()
        );
    }

    /** @test */
    public function testIncoterm()
    {
        $customs = new Customs();
        $this->assertEquals(
            Customs::INCOTERM_DUTY_DELIVERY_PAID,
            $customs->setIncoterm(Customs::INCOTERM_DUTY_DELIVERY_PAID)->getIncoterm()
        );
    }

    /** @test */
    public function testInvoiceNumber()
    {
        $customs = new Customs();
        $this->assertEquals('000314156', $customs->setInvoiceNumber('000314156')->getInvoiceNumber());
    }

    /** @test */
    public function testNonDelivery()
    {
        $customs = new Customs();
        $this->assertEquals(Customs::NON_DELIVERY_RETURN, $customs->setNonDelivery(Customs::NON_DELIVERY_RETURN)->getNonDelivery());
    }

    /** @test */
    public function testItems()
    {
        $customs = new Customs();

        $this->assertEmpty($customs->getItems());

        $items = [
            Mockery::mock(CustomsItem::class),
            Mockery::mock(CustomsItem::class),
            Mockery::mock(CustomsItem::class),
        ];

        $customs->setItems($items);
        $this->assertCount(3, $customs->getItems());
        $this->assertEquals($items, $customs->getItems());

        $item = Mockery::mock(CustomsItem::class);
        $items[] = $item;
        $customs->addItem($item);
        $this->assertCount(4, $customs->getItems());
        $this->assertEquals($items, $customs->getItems());
    }
}
