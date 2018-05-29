<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\PickUpDropOffLocations;

use Illuminate\Http\JsonResponse;
use MyParcelCom\JsonApi\Transformers\TransformerService;
use MyParcelCom\Microservice\Http\Controllers\Controller;
use MyParcelCom\Microservice\Http\Request;

class PickUpDropOffLocationController extends Controller
{
    public function getAll(
        PickUpDropOffLocationRepository $pickUpDropOffLocationRepository,
        TransformerService $transformerService,
        Request $request,
        string $countryCode,
        string $postalCode
    ): JsonResponse {
        $filters = $request->getFilter();
        $categories = isset($filters['categories'])
            ? explode(',', $filters['categories'])
            : [];

        $pudoLocations = $pickUpDropOffLocationRepository->getAll(
            $countryCode,
            $postalCode,
            $request->query('street', null),
            $request->query('street_number', null),
            $categories
        );

        $response = $transformerService->transformResources($pudoLocations);

        return new JsonResponse($response);
    }
}
