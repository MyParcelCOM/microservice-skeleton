<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Feature;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Tests\Traits\CommunicatesWithCarrier;

class JsonApiResponseHeaderTest extends TestCase
{
    use CommunicatesWithCarrier;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bindCarrierApiGatewayMock();
    }

    /** @test */
    public function itTransformsJsonAcceptHeaderToJsonApiHeader()
    {
        Route::get('/foo', function () {
            return new Response('Bar', 200, ['Content-Type' => 'application/json']);
        });

        $response = $this->json('GET', '/foo', [], $this->getRequestHeaders());

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
    }

    /** @test */
    public function itDoesNotTransformOtherAcceptHeaders()
    {
        Route::get('/foo', function () {
            return new Response('Bar', 200, ['Content-Type' => 'application/something-else']);
        });

        $response = $this->json('GET', '/foo', [], $this->getRequestHeaders());

        $response->assertHeader('Content-Type', 'application/something-else');
    }
}
