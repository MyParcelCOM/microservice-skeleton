<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\PickUpDropOffLocations;

use Illuminate\Http\JsonResponse;
use MyParcelCom\JsonApi\Transformers\TransformerService;
use MyParcelCom\Microservice\Http\Controllers\Controller;

class PickUpDropOffLocationController extends Controller
{
    public function getAll(
        PickUpDropOffLocationRepository $pickUpDropOffLocationRepository,
        TransformerService $transformerService,
        string $countryCode,
        string $postalCode,
        string $street = null,
        string $streetNumber = null
    ): JsonResponse {
        $pudoLocations = $pickUpDropOffLocationRepository->getAll($countryCode, $postalCode, $street, $streetNumber);

        $response = $transformerService->transformResources($pudoLocations);

        return new JsonResponse($response);
    }
}
