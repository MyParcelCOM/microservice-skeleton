<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Credentials;

use Illuminate\Http\JsonResponse;
use MyParcelCom\Microservice\Carrier\CarrierApiGatewayInterface;
use MyParcelCom\Microservice\Http\Controllers\Controller;

class CredentialController extends Controller
{
    /**
     * Validates credentials provided in the X-MYPARCELCOM-CREDENTIALS header.
     */
    public function validateCredentials(CarrierApiGatewayInterface $carrierApiGateway): JsonResponse
    {
        // TODO: implement validation check
        // Some carriers may have dedicated endpoints for this. Otherwise you could try one of their GET endpoints and
        // see if it comes up with any erroneous status codes related to invalid credentials.
        $valid = false;

        if (!$valid) {
            return $this->invalidResponse('Credentials given are invalid');
        }

        return $this->validResponse();
    }

    private function validResponse(): JsonResponse
    {
        return new JsonResponse([
            'data' => [
                'valid' => true,
            ],
        ], JsonResponse::HTTP_OK);
    }

    private function invalidResponse(string $message = '', $statusCode = JsonResponse::HTTP_BAD_REQUEST): JsonResponse
    {
        return new JsonResponse([
            'data' => [
                'valid'   => false,
                'message' => $message,
            ],
        ], $statusCode);
    }
}
