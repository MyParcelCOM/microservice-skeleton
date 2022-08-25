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
     * Route that validates and creates a shipment.
     *
     * @param JsonRequestValidator $jsonRequestValidator
     * @param ShipmentRepository   $repository
     * @param ShipmentRequest      $request
     * @param TransformerService   $transformerService
     * @return JsonResponse
     * @throws InvalidJsonSchemaException
     * @throws TransformerException
     */
    public function create(
        JsonRequestValidator $jsonRequestValidator,
        ShipmentRepository $repository,
        ShipmentRequest $request,
        TransformerService $transformerService
    ): JsonResponse {
        $jsonRequestValidator->validate('/shipments', 'post');

        $shipment = $repository->createFromPostData($request->json('data'), $request->json('meta', []));

        return new JsonResponse(
            $transformerService->transformResource($shipment),
            JsonResponse::HTTP_CREATED
        );
    }


    /**
     * @param JsonRequestValidator      $jsonRequestValidator
     * @param ShipmentRepository        $repository
     * @param MultiColliShipmentRequest $request
     * @param TransformerService        $transformerService
     * @return JsonResponse
     */
    public function createMultiColli(
        JsonRequestValidator $jsonRequestValidator,
        ShipmentRepository $repository,
        MultiColliShipmentRequest $request,
        TransformerService $transformerService
    ): JsonResponse {
        $jsonRequestValidator->validate('/multi-colli-shipments', 'post', null);

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
            JsonResponse::HTTP_CREATED
        );
    }

    /**
     * @param string $shipmentId
     * @return JsonResponse
     */
    public function void(string $shipmentId)
    {
        // TODO: implement void / cancel functionality or return true if the carrier does not charge for unused labels.
        $voided = false;

        return new JsonResponse(
            '',
            $voided ? JsonResponse::HTTP_NO_CONTENT : JsonResponse::HTTP_LOCKED
        );
    }
}
