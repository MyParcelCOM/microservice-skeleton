<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Statuses;

use Illuminate\Http\JsonResponse;
use MyParcelCom\Microservice\Http\Controllers\Controller;
use MyParcelCom\Transformers\TransformerService;

class StatusController extends Controller
{
    /**
     * @param string             $shipmentId
     * @param string             $barcode
     * @param StatusRepository   $statusRepository
     * @param TransformerService $transformerService
     * @return JsonResponse
     * @internal param Shipment $shipment
     */
    public function getStatuses(string $shipmentId, string $barcode, StatusRepository $statusRepository, TransformerService $transformerService): JsonResponse
    {
        $statuses = $statusRepository->getStatuses($shipmentId, $barcode);

        return new JsonResponse(
            $transformerService->transformResources($statuses)
        );
    }
}
