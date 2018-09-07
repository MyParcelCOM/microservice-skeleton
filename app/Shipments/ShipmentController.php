<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Shipments;

use Illuminate\Http\JsonResponse;
use MyParcelCom\JsonApi\Exceptions\InvalidJsonSchemaException;
use MyParcelCom\JsonApi\Transformers\TransformerService;
use MyParcelCom\Microservice\Http\Controllers\Controller;
use MyParcelCom\Microservice\Http\JsonRequestValidator;
use MyParcelCom\Microservice\Http\Request;
use MyParcelCom\Microservice\Validation\ApiRequestValidator;

class ShipmentController extends Controller
{
    /**
     * Route that validates and creates a shipment.
     *
     * @param JsonRequestValidator $jsonRequestValidator
     * @param ApiRequestValidator  $apiRequestValidator
     * @param ShipmentRepository   $repository
     * @param Request              $request
     * @param TransformerService   $transformerService
     * @return JsonResponse
     * @throws InvalidJsonSchemaException
     * @throws \MyParcelCom\JsonApi\Transformers\TransformerException
     */
    public function create(
        JsonRequestValidator $jsonRequestValidator,
        ApiRequestValidator $apiRequestValidator,
        ShipmentRepository $repository,
        Request $request,
        TransformerService $transformerService
    ): JsonResponse {
        $jsonRequestValidator->validate('/shipments', 'post', null);

        // TODO Add rules to ApiRequestValidator to include carrier-specific requirements.
        if (!$apiRequestValidator->validate($request)) {
            $errors = $apiRequestValidator->getErrors();

            throw new InvalidJsonSchemaException($errors);
        }

        $shipment = $repository->createFromPostData($request->json('data'), $request->json('meta'));

        return new JsonResponse(
            $transformerService->transformResource($shipment),
            JsonResponse::HTTP_CREATED
        );
    }
}
