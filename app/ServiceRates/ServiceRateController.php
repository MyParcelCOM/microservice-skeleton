<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\ServiceRates;

use Illuminate\Http\JsonResponse;
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

        return new JsonResponse(
            $transformerService->transformResources($serviceRates)
        );
    }
}
