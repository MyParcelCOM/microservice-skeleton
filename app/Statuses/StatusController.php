<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Statuses;

use Illuminate\Http\JsonResponse;
use MyParcelCom\JsonApi\Transformers\TransformerService;
use MyParcelCom\Microservice\Http\Controllers\Controller;

class StatusController extends Controller
{
    /**
     * @param string             $shipmentId
     * @param string             $trackingCode
     * @param StatusRepository   $statusRepository
     * @param TransformerService $transformerService
     * @return JsonResponse
     */
    public function getStatuses(
        string $shipmentId,
        string $trackingCode,
        StatusRepository $statusRepository,
        TransformerService $transformerService
    ): JsonResponse {
        $statuses = $statusRepository->getStatuses($shipmentId, $trackingCode);

        return new JsonResponse(
            $transformerService->transformResources($statuses)
        );
    }
}
