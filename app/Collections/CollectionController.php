<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Collections;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use MyParcelCom\JsonApi\Exceptions\ResourceConflictException;
use MyParcelCom\JsonApi\Exceptions\ResourceNotFoundException;
use MyParcelCom\JsonApi\Transformers\TransformerService;
use MyParcelCom\Microservice\CollectionTimeSlots\CollectionTimeSlotRepository;
use MyParcelCom\Microservice\Http\JsonRequestValidator;
use MyParcelCom\Microservice\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CollectionController
{
    /**
     * Route that validates and creates a shipment.
     */
    public function create(
        JsonRequestValidator $jsonRequestValidator,
        CollectionRepository $repository,
        Request $request,
        TransformerService $transformerService
    ): JsonResponse {
        $jsonRequestValidator->validate('/collections', 'post');

        $collection = $repository->createFromPostData($request->json('data'));

        return new JsonResponse(
            $transformerService->transformResource($collection),
            JsonResponse::HTTP_CREATED
        );
    }

    public function update(
        string $collectionId,
        JsonRequestValidator $jsonRequestValidator,
        CollectionRepository $repository,
        Request $request,
        TransformerService $transformerService
    ): JsonResponse {
        $jsonRequestValidator->validate('/collections/{collection_id}', 'patch');

        if ($collectionId !== $request->json('data.id')) {
            throw new ResourceConflictException('id');
        }

        $updatedCollection = $repository->updateFromPatchData($collectionId, $request->json('data'));

        return new JsonResponse(
            $transformerService->transformResource($updatedCollection),
        );
    }

    /**
     * @param string               $collectionId
     * @param CollectionRepository $repository
     * @return JsonResponse
     * @throws ResourceNotFoundException
     */
    public function void(
        string $collectionId,
        CollectionRepository $repository,
    ): JsonResponse {
        // TODO: implement void / cancel functionality.
        $repository->delete($collectionId);

        return new JsonResponse(
            '',
            Response::HTTP_NO_CONTENT
        );
    }

    public function getTimeSlots(
        JsonRequestValidator $jsonRequestValidator,
        Request $request,
        CollectionTimeSlotRepository $collectionTimeSlotRepository,
        TransformerService $transformerService
    ): JsonResponse {
        $jsonRequestValidator->validate('/collection-time-slots', 'get');

        // todo: Fill in Carrier specific request details in the CollectionTimeSlotRepository.
        $timeSlots = $collectionTimeSlotRepository->getCollectionTimeSlots(
            $request->query('country_code'),
            $request->query('postal_code'),
            new Carbon($request->query('date_from')),
            new Carbon($request->query('date_to')),
            $request->query('service_code')
        );

        return new JsonResponse(
            $transformerService->transformResources($timeSlots)
        );
    }
}
