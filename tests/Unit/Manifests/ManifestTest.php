<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Manifests;

use MyParcelCom\Microservice\Manifests\Manifest;
use MyParcelCom\Microservice\Shipments\File;
use MyParcelCom\Microservice\Tests\TestCase;

class ManifestTest extends TestCase
{
    private Manifest $manifest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->manifest = new Manifest('my-test-manifest');
    }

    public function testItGetsAName(): void
    {
        $this->assertEquals('my-test-manifest', $this->manifest->getName());
    }

    /** @test */
    public function testItSetsFiles()
    {
        $file = new File();
        $files = [$file];
        $this->assertEquals($files, $this->manifest->addFile($file)->getFiles());
    }
}
