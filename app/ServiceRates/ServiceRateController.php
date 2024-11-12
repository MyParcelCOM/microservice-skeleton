<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\ServiceRates;

use Illuminate\Http\JsonResponse;
use MyParcelCom\JsonApi\Http\Paginator;
use MyParcelCom\JsonApi\Transformers\TransformerService;
use MyParcelCom\Microservice\Http\Controllers\Controller;
use MyParcelCom\Microservice\Http\JsonRequestValidator;
use MyParcelCom\Microservice\Http\MultiColliShipmentRequest;
use MyParcelCom\Microservice\Http\Request;
use MyParcelCom\Microservice\Shipments\Shipment;
use MyParcelCom\Microservice\Shipments\ShipmentRepository;

class ServiceRateController extends Controller
{
    public function getServiceRates(
        ServiceRateRepository $serviceRateRepository,
        TransformerService $transformerService,
        Request $request
    ): JsonResponse {
        $serviceRates = $serviceRateRepository->getServiceRates($request->json('data'));

        // Handle pagination
        $page = array_merge(
            ['size' => 100, 'number' => 1],
            request()->input('page') ?? []
        );
        $paginator = (new Paginator('/' . request()->path(), (int) $page['size'], (int) $page['number']))->setMaxPageSize((int) $page['size']);

        return new JsonResponse(
            $transformerService->setPaginator($paginator)->transformResources($serviceRates)
        );
    }

    public function getMultiColliServiceRates(
        JsonRequestValidator $jsonRequestValidator,
        MultiColliShipmentRequest $request,
        ServiceRateRepository $serviceRateRepository,
        TransformerService $transformerService,
    ): JsonResponse {
        $jsonRequestValidator->validate('/get-multi-colli-service-rates', 'post');

        $serviceRates = $serviceRateRepository->getMultiColliServiceRates($request->json('data'));

        $page = array_merge(
            ['size' => 100, 'number' => 1],
            request()->input('page') ?? []
        );
        $paginator = (new Paginator('/' . request()->path(), (int) $page['size'], (int) $page['number']))->setMaxPageSize((int) $page['size']);

        return new JsonResponse(
            $transformerService->setPaginator($paginator)->transformResources($serviceRates)
        );
    }
}
