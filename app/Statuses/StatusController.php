<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Statuses;

use Illuminate\Http\JsonResponse;
use MyParcelCom\JsonApi\Http\Paginator;
use MyParcelCom\JsonApi\Transformers\TransformerService;
use MyParcelCom\Microservice\Http\Controllers\Controller;
use MyParcelCom\Microservice\Http\Request;

class StatusController extends Controller
{
    public function getStatuses(
        Request $request,
        string $shipmentId,
        string $trackingCode,
        StatusRepository $statusRepository,
        TransformerService $transformerService,
    ): JsonResponse {
        $statuses = $statusRepository->getStatuses($shipmentId, $trackingCode);

        // Handle pagination
        $page = array_merge(
            ['size' => 100, 'number' => 1],
            $request->input('page') ?? [],
        );
        $paginator = new Paginator('/' . $request->path(), (int) $page['size'], (int) $page['number']);
        $paginator->setMaxPageSize((int) $page['size']);

        return new JsonResponse(
            $transformerService->setPaginator($paginator)->transformResources($statuses),
        );
    }
}
