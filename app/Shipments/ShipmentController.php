<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Shipments;

use Illuminate\Http\JsonResponse;
use MyParcelCom\Microservice\Http\Controllers\Controller;
use MyParcelCom\Microservice\Http\JsonRequestValidator;
use MyParcelCom\Microservice\Http\Request;
use MyParcelCom\Transformers\TransformerService;

class ShipmentController extends Controller
{
    /**
     * Route that validates and creates a shipment.
     *
     * @param JsonRequestValidator $validator
     * @param ShipmentRepository   $repository
     * @param Request              $request
     * @param TransformerService   $transformerService
     * @return JsonResponse
     */
    public function create(JsonRequestValidator $validator, ShipmentRepository $repository, Request $request, TransformerService $transformerService): JsonResponse
    {
        $validator->validate('/shipments', 'post', 201);

        // TODO Edit ShipmentValidator to include carrier-specific requirements.
        if (($errors = $this->shipmentValidator->validate($shipment))) {
            throw new InvalidJsonSchemaException($errors);
        }

        $shipment = $repository->createFromPostData($request->json('data'));

        return new JsonResponse(
            $transformerService->transformResource($shipment),
            JsonResponse::HTTP_CREATED
        );
    }
}
