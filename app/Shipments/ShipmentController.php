<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Shipments;

use Illuminate\Http\JsonResponse;
use MyParcelCom\JsonApi\Exceptions\InvalidJsonSchemaException;
use MyParcelCom\JsonApi\Transformers\TransformerException;
use MyParcelCom\JsonApi\Transformers\TransformerService;
use MyParcelCom\Microservice\Http\Controllers\Controller;
use MyParcelCom\Microservice\Http\JsonRequestValidator;
use MyParcelCom\Microservice\Http\MultiColliShipmentRequest;
use MyParcelCom\Microservice\Http\ShipmentRequest;

class ShipmentController extends Controller
{
    /**
     * @throws InvalidJsonSchemaException
     * @throws TransformerException
     */
    public function create(
        JsonRequestValidator $jsonRequestValidator,
        ShipmentRepository $repository,
        ShipmentRequest $request,
        TransformerService $transformerService,
    ): JsonResponse {
        $jsonRequestValidator->validate('/shipments', 'post');

        $shipment = $repository->createFromPostData($request->json('data'), $request->json('meta', []));

        return new JsonResponse(
            $transformerService->transformResource($shipment),
            JsonResponse::HTTP_CREATED,
        );
    }

    public function createMultiColli(
        JsonRequestValidator $jsonRequestValidator,
        ShipmentRepository $repository,
        MultiColliShipmentRequest $request,
        TransformerService $transformerService,
    ): JsonResponse {
        $jsonRequestValidator->validate('/multi-colli-shipments', 'post');

        $response = $repository->createFromMultiColliPostData($request->json('data'), $request->json('meta', []));

        return new JsonResponse(
            [
                'data' => [
                    'master' => $transformerService->transformResource($response['master'])['data'],
                    'colli'  => $response['colli']->map(function (Shipment $collo) use ($transformerService) {
                        return $transformerService->transformResource($collo)['data'];
                    }),
                ],
            ],
            JsonResponse::HTTP_CREATED,
        );
    }

    public function void(string $shipmentId): JsonResponse
    {
        // TODO: implement void / cancel functionality or return true if the carrier does not charge for unused labels.
        $voided = false;

        return new JsonResponse(
            '',
            $voided ? JsonResponse::HTTP_NO_CONTENT : JsonResponse::HTTP_LOCKED,
        );
    }

    public function getShipmentService(string $shipmentId): JsonResponse
    {
        // TODO: fetch service connected to the Shipment from the carrier
        //  map the carrier response to a service code and name and return them

        $service = new Service();

        return new JsonResponse([
            'data' => array_filter([
                'name' => $service->getName(),
                'code' => $service->getCode(),
            ]),
        ]);
    }
}
