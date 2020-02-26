<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Shipments;

use Illuminate\Http\JsonResponse;
use MyParcelCom\JsonApi\Exceptions\InvalidJsonSchemaException;
use MyParcelCom\JsonApi\Transformers\TransformerException;
use MyParcelCom\JsonApi\Transformers\TransformerService;
use MyParcelCom\Microservice\Http\Controllers\Controller;
use MyParcelCom\Microservice\Http\JsonRequestValidator;
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
        $jsonRequestValidator->validate('/shipments', 'post', null);

        $shipment = $repository->createFromPostData($request->json('data'), $request->json('meta', []));

        return new JsonResponse(
            $transformerService->transformResource($shipment),
            JsonResponse::HTTP_CREATED
        );
    }

    public function void(){
        $voidedSuccess = false; // TODO: implement void / cancel functionality if necessary

        return new JsonResponse(
            $voidedSuccess ? 'The shipment has been voided.' : 'The shipment could not be voided.',
            $voidedSuccess ? JsonResponse::HTTP_NO_CONTENT : JsonResponse::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
