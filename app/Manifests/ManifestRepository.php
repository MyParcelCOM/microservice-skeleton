<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Manifests;

use MyParcelCom\Microservice\Carrier\CarrierApiGatewayInterface;

class ManifestRepository
{

    /**
     * @param CarrierApiGatewayInterface $carrierApiGateway
     * @param ManifestMapper             $manifestMapper
     */
    public function __construct(
        private CarrierApiGatewayInterface $carrierApiGateway,
        private ManifestMapper $manifestMapper,
    ) {
    }

    /**
     * Makes a manifest from the posted manifest data and persists it (by sending it to the carrier api).
     *
     * @param array $data
     * @param array $meta
     * @return Manifest
     */
    public function createFromPostData(array $data, array $meta = []): Manifest
    {
        /** @var Manifest $manifest */
        $manifest = $this->manifestMapper->map($data, new Manifest($data['name']));

        // TODO: Validate the data for this specific carrier.
        // TODO: Map/transform the Manifest to a valid request for the carrier.
        // TODO: Send the manifest to the carrier (use CarrierApiGateway).
        // TODO: Map updated values to the Manifest (barcode, id, etc).
        // TODO: Get files (label, printcode, etc) for the manifest.
        // TODO: Add files to the manifest (use File objects).

        return $manifest;
    }
}
