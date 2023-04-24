<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Manifests;

use MyParcelCom\Microservice\Carrier\CarrierApiGatewayInterface;

class ManifestRepository
{
    public function __construct(
        private readonly CarrierApiGatewayInterface $carrierApiGateway,
        private readonly ManifestMapper $manifestMapper,
    ) {
    }

    /**
     * Makes a manifest from the posted manifest data and persists it (by sending it to the carrier api).
     */
    public function createFromPostData(array $data, array $meta = []): Manifest
    {
        $manifest = $this->manifestMapper->map($data);

        // TODO: Validate the data for this specific carrier.
        // TODO: Map/transform the Manifest to a valid request for the carrier.
        // TODO: Send the manifest to the carrier (use CarrierApiGateway).
        // TODO: Map updated values to the Manifest (barcode, id, etc).
        // TODO: Get files (label, printcode, etc) for the manifest.
        // TODO: Add files to the manifest (use File objects).

        return $manifest;
    }
}
