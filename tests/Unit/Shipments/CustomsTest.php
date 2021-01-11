<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Shipments;

use MyParcelCom\Microservice\Shipments\Customs;
use PHPUnit\Framework\TestCase;

class CustomsTest extends TestCase
{
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
        $this->assertNull($customs->getInvoiceNumber());
        $this->assertEquals('000314156', $customs->setInvoiceNumber('000314156')->getInvoiceNumber());
    }

    /** @test */
    public function testNonDelivery()
    {
        $customs = new Customs();
        $this->assertEquals(Customs::NON_DELIVERY_RETURN, $customs->setNonDelivery(Customs::NON_DELIVERY_RETURN)->getNonDelivery());
    }


    /** @test */
    public function testShippingValue()
    {
        $customs = new Customs();

        $this->assertNull($customs->getShippingValueAmount());
        $this->assertNull($customs->getShippingValueCurrency());

        $customs->setShippingValueAmount(12345);
        $customs->setShippingValueCurrency('GBP');

        $this->assertEquals(12345, $customs->getShippingValueAmount());
        $this->assertEquals('GBP', $customs->getShippingValueCurrency());
    }
}
