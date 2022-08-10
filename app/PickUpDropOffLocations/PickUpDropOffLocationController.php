<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\PickUpDropOffLocations;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use MyParcelCom\JsonApi\Transformers\TransformerService;
use MyParcelCom\Microservice\Http\Controllers\Controller;
use MyParcelCom\Microservice\Http\Request;

class PickUpDropOffLocationController extends Controller
{
    public function getAllByCountryAndPostalCode(
        PickUpDropOffLocationRepository $pickUpDropOffLocationRepository,
        TransformerService $transformerService,
        Request $request,
        string $countryCode,
        string $postalCode
    ): JsonResponse {
        $filters = $request->getFilter();
        $categories = array_filter(explode(',', Arr::get($filters, 'categories', '')));
        $features = array_filter(explode(',', Arr::get($filters, 'features', '')));
        $locationType = array_filter(explode(',', Arr::get($filters, 'location_type', '')));

        $pudoLocations = $pickUpDropOffLocationRepository->getAllByCountryAndPostalCode(
            $countryCode,
            $postalCode,
            $request->query('street'),
            $request->query('street_number'),
            $request->query('city'),
            $categories,
            $features,
            $locationType
        );

        $response = $transformerService->transformResources($pudoLocations);

        return new JsonResponse($response);
    }

    public function getAllByGeolocation(
        PickUpDropOffLocationRepository $pickUpDropOffLocationRepository,
        TransformerService $transformerService,
        Request $request,
        string $latitude,
        string $longitude
    ): JsonResponse {
        $filters = $request->getFilter();
        $categories = array_filter(explode(',', Arr::get($filters, 'categories', '')));
        $features = array_filter(explode(',', Arr::get($filters, 'features', '')));
        $radius = Arr::has($filters, 'radius') ? (int) Arr::get($filters, 'radius') : null;
        $locationType = array_filter(explode(',', Arr::get($filters, 'location_type', '')));

        $pudoLocations = $pickUpDropOffLocationRepository->getAllByGeolocation(
            $latitude,
            $longitude,
            $radius,
            $categories,
            $features,
            $locationType
        );

        $response = $transformerService->transformResources($pudoLocations);

        return new JsonResponse($response);
    }
}
