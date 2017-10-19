<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Feature;

use MyParcelCom\Microservice\Tests\TestCase;
use MyParcelCom\Microservice\Tests\Traits\CommunicatesWithCarrier;
use Symfony\Component\HttpFoundation\Response;

class VerifySecretTest extends TestCase
{
    use CommunicatesWithCarrier;

    /** @test */
    public function aRequestNeedsAValidSecret()
    {
        $validRequest = $this->json('GET', '/', [], $this->getRequestHeaders());
        $validRequest->assertStatus(200);
    }

    /** @test */
    public function aRequestWithoutSecretFails()
    {
        $invalidRequest = $this->json('GET', '/');
        $invalidRequest->assertStatus(Response::HTTP_UNAUTHORIZED);
        $invalidRequest->assertJson([
            'errors' => [
                [
                    'status' => '401',
                    'code'   => '13003',
                    'title'  => 'Invalid Secret',
                ],
            ],
        ]);
    }

    /** @test */
    public function aRequestWithAnInvalidSecretFails()
    {
        $invalidRequest = $this->json('GET', '/', [], [
            'X-MYPARCELCOM-SECRET' => 'i-am-invalid',
        ]);
        $invalidRequest->assertStatus(Response::HTTP_UNAUTHORIZED);
        $invalidRequest->assertJson([
            'errors' => [
                [
                    'status' => '401',
                    'code'   => '13003',
                    'title'  => 'Invalid Secret',
                ],
            ],
        ]);
    }
}
