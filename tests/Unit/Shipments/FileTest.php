<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Shipments;

use MyParcelCom\Microservice\Shipments\File;
use MyParcelCom\Microservice\Shipments\Shipment;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    /** @test */
    public function testType()
    {
        $file = new File();
        $this->assertEquals('invoice', $file->setType('invoice')->getType());
    }

    /** @test */
    public function testMimeType()
    {
        $file = new File();
        $file->setMimeType(Shipment::LABEL_MIME_TYPE_PDF);
        $this->assertEquals(Shipment::LABEL_MIME_TYPE_PDF, $file->getMimeType());
    }

    /** @test */
    public function testExtension()
    {
        $file = new File();
        $this->assertEquals('pdf', $file->setExtension('pdf')->getExtension());
    }

    /** @test */
    public function testData()
    {
        $file = new File();
        $this->assertEquals(
            'bfksanfgsgDFGEH#45y3kgbhwegESGDbghekrgbewr',
            $file->setData('bfksanfgsgDFGEH#45y3kgbhwegESGDbghekrgbewr')->getData(),
        );
    }

    /** @test */
    public function testSetDataFromPath()
    {
        $file = new File();
        $contents = base64_encode(file_get_contents(base_path('tests/Stubs/document.pdf')));
        $this->assertEquals(
            $contents,
            $file->setDataFromPath(base_path('tests/Stubs/document.pdf'))->getData(),
        );
    }
}
