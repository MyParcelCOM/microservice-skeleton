<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\ServiceRates;

use Illuminate\Http\JsonResponse;
use MyParcelCom\JsonApi\Http\Paginator;
use MyParcelCom\JsonApi\Transformers\TransformerService;
use MyParcelCom\Microservice\Http\Controllers\Controller;
use MyParcelCom\Microservice\Shipments\Shipment;

class ServiceRateController extends Controller
{
    /**
     * @param Shipment              $shipment
     * @param ServiceRateRepository $serviceRateRepository
     * @param TransformerService    $transformerService
     * @return JsonResponse
     */
    public function getServiceRates(
        Shipment $shipment,
        ServiceRateRepository $serviceRateRepository,
        TransformerService $transformerService
    ): JsonResponse {
        $serviceRates = $serviceRateRepository->getServiceRates($shipment);

        // Handle pagination
        $page = array_merge(
            ['size' => 100, 'number' => 1],
            request()->input('page') ?? []
        );
        $paginator = (
        new Paginator(
            '/' . request()->path(),
            (int) $page['size'],
            (int) $page['number']
        )
        )->setMaxPageSize((int) $page['size']);

        return new JsonResponse(
            $transformerService->setPaginator($paginator)->transformResources($serviceRates)
        );
    }
}
