<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Credentials;

use Illuminate\Http\JsonResponse;
use MyParcelCom\Microservice\Carrier\CarrierApiGatewayInterface;
use MyParcelCom\Microservice\Http\Controllers\Controller;

class CredentialController extends Controller
{
    /**
     * @var CarrierApiGatewayInterface
     */
    protected $carrierApiGateway;

    /**
     * @param CarrierApiGatewayInterface $carrierApiGateway
     */
    public function __construct(CarrierApiGatewayInterface $carrierApiGateway)
    {
        $this->carrierApiGateway = $carrierApiGateway;
    }

    /**
     * Validates credentials in the header
     *
     * @return JsonResponse
     */
    public function validateCredentials(): JsonResponse
    {

        // TODO: implement validation check
        // Some carriers may have dedicated endpoints
        // for this. Otherwise you could try a random
        // GET endpoint and see if it comes up with
        // any erroneous status codes related to
        // invalid credentials
        $valid = true;

        if (!$valid) {
            return $this->invalidResponse('Credentials given are invalid');
        }

        return new JsonResponse([
            'data' => [
                'valid' => true,
            ],
        ], JsonResponse::HTTP_OK);
    }

    /**
     * @param string $message
     * @param int    $statusCode
     * @return JsonResponse
     */
    protected function invalidResponse(string $message = '', $statusCode = JsonResponse::HTTP_BAD_REQUEST): JsonResponse
    {
        return new JsonResponse([
            'data' => [
                'valid'   => false,
                'message' => $message,
            ],
        ], $statusCode);
    }
}
