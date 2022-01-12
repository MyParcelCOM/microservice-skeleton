<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Manifests;

use MyParcelCom\Microservice\Manifests\Manifest;
use MyParcelCom\Microservice\Model\Json\AddressJson;
use MyParcelCom\Microservice\Model\Json\ContactJson;
use MyParcelCom\Microservice\Shipments\File;
use MyParcelCom\Microservice\Tests\TestCase;

class ManifestTest extends TestCase
{
    private Manifest $manifest;

    protected function setUp(): void
    {
        parent::setUp();

        $addressData = [
            'street_1'     => 'Binnenhof',
            'city'         => 'Den Haag',
            'country_code' => 'NL',
            'company'      => 'Lockdown BV',
        ];

        $this->manifest = new Manifest(
            'my-test-manifest',
            new AddressJson($addressData),
            new ContactJson($addressData)
        );
    }

    public function testItGetsAName(): void
    {
        $this->assertEquals('my-test-manifest', $this->manifest->getName());
    }

    public function testItGetsAnAddress(): void
    {
        $this->assertEquals(
            [
                'street_1'     => 'Binnenhof',
                'city'         => 'Den Haag',
                'country_code' => 'NL',
            ],
            array_filter($this->manifest->getAddressJson()->toArray())
        );
        $this->assertEquals(
            [
                'company' => 'Lockdown BV',
            ],
            array_filter($this->manifest->getContactJson()->toArray())
        );
    }

    /** @test */
    public function testItSetsFiles()
    {
        $file = new File();
        $files = [$file];
        $this->assertEquals($files, $this->manifest->addFile($file)->getFiles());
    }
}
