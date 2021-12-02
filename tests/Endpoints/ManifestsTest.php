<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Endpoints;

use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Tests\Traits\CommunicatesWithCarrier;
use MyParcelCom\Microservice\Tests\Traits\JsonApiAssertionsTrait;

/**
 * @group Endpoints:Manifest
 * @group Implementation
 */
class ManifestsTest extends TestCase
{
    use CommunicatesWithCarrier;
    use JsonApiAssertionsTrait;

    /** @test */
    public function testGetManifests()
    {
        $this->bindHttpClientMock();

        // TODO: Add carrier response stub for requesting a status.
        // See the "Response Stubs" chapter in the readme for more info.

        $this->assertJsonSchema(
            '/manifests',
            '/manifests',
            $this->getRequestHeaders()
        );
    }
}
