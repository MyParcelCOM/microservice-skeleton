<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Shipments;

use MyParcelCom\JsonApi\Exceptions\ModelTypeException;
use MyParcelCom\JsonApi\Transformers\AbstractTransformer;
use MyParcelCom\Microservice\PickUpDropOffLocations\Address;

use function array_filter;

class ShipmentTransformer extends AbstractTransformer
{
    /** @var string */
    protected $type = 'shipments';

    /**
     * @param Shipment $shipment
     * @return string
     */
    public function getId($shipment): string
    {
        $this->validateModel($shipment);

        return $shipment->getId();
    }

    /**
     * @param Shipment $shipment
     * @return array
     */
    public function getAttributes($shipment): array
    {
        $this->validateModel($shipment);

        return array_filter([
            'myparcelcom_shipment_id'              => $shipment->getMyparcelcomShipmentId(),
            'recipient_address'                    => $this->transformAddress($shipment->getRecipientAddress()),
            'recipient_tax_number'                 => $shipment->getRecipientTaxNumber(),
            'recipient_tax_identification_numbers' => $shipment->getRecipientTaxIdentificationNumbers(),
            'sender_address'                       => $this->transformAddress($shipment->getSenderAddress()),
            'sender_tax_number'                    => $shipment->getSenderTaxNumber(),
            'sender_tax_identification_numbers'    => $shipment->getSenderTaxIdentificationNumbers(),
            'tax_identification_numbers'           => $shipment->getTaxIdentificationNumbers(),
            'return_address'                       => $this->transformAddress($shipment->getReturnAddress()),
            'pickup_location'                      => $shipment->getPickupLocationCode() === null ? null : [
                'code'    => $shipment->getPickupLocationCode(),
                'address' => $this->transformAddress($shipment->getPickupLocationAddress()),
            ],
            'description'                          => $shipment->getDescription(),
            'customer_reference'                   => $shipment->getCustomerReference(),
            'total_value'                          => $shipment->getTotalValueAmount() === null ? null : [
                'amount'   => $shipment->getTotalValueAmount(),
                'currency' => $shipment->getTotalValueCurrency(),
            ],
            'barcode'                              => $shipment->getBarcode(),
            'tracking_code'                        => $shipment->getTrackingCode(),
            'tracking_url'                         => $shipment->getTrackingUrl(),
            'service'                              => [
                'code' => $shipment->getService()->getCode(),
                'name' => $shipment->getService()->getName(),
            ],
            'options'                              => array_map(function (Option $option) {
                return array_filter([
                    'code'   => $option->getCode(),
                    'name'   => $option->getName(),
                    'values' => $option->getValues() ? array_filter($option->getValues()) : null,
                ]);
            }, $shipment->getOptions()),
            'physical_properties'                  => $shipment->getPhysicalProperties() === null ? null : array_filter([
                'height'            => $shipment->getPhysicalProperties()->getHeight(),
                'width'             => $shipment->getPhysicalProperties()->getWidth(),
                'length'            => $shipment->getPhysicalProperties()->getLength(),
                'volume'            => $shipment->getPhysicalProperties()->getVolume(),
                'weight'            => $shipment->getPhysicalProperties()->getWeight(),
                'volumetric_weight' => $shipment->getPhysicalProperties()->getVolumetricWeight(),
            ]),
            'files'                                => array_map(function (File $file) {
                return [
                    'resource_type' => $file->getType(),
                    'mime_type'     => $file->getMimeType(),
                    'extension'     => $file->getExtension(),
                    'data'          => $file->getData(),
                ];
            }, $shipment->getFiles()),
            'items'                                => array_map(function (ShipmentItem $item) {
                return array_filter([
                    'sku'                 => $item->getSku(),
                    'description'         => $item->getDescription(),
                    'image_url'           => $item->getImageUrl(),
                    'quantity'            => $item->getQuantity(),
                    'hs_code'             => $item->getHsCode(),
                    'origin_country_code' => $item->getOriginCountryCode(),
                    'item_value'          => $item->getItemValueAmount() === null ? null : [
                        'amount'   => $item->getItemValueAmount(),
                        'currency' => $item->getItemValueCurrency(),
                    ],
                    'tax'                 => $item->getTaxAmount() === null ? null : [
                        'amount'   => $item->getTaxAmount(),
                        'currency' => $item->getTaxCurrency(),
                    ],
                    'duty'                => $item->getDutyAmount() === null ? null : [
                        'amount'   => $item->getDutyAmount(),
                        'currency' => $item->getDutyCurrency(),
                    ],
                    'vat_percentage'      => $item->getVatPercentage(),
                ]);
            }, $shipment->getItems()),
            'customs'                              => $shipment->getCustoms() === null ? null : array_filter([
                'content_type'   => $shipment->getCustoms()->getContentType(),
                'invoice_number' => $shipment->getCustoms()->getInvoiceNumber(),
                'non_delivery'   => $shipment->getCustoms()->getNonDelivery(),
                'incoterm'       => $shipment->getCustoms()->getIncoterm(),
                'shipping_value' => $shipment->getCustoms()->getShippingValueAmount() === null ? null : [
                    'amount'   => $shipment->getCustoms()->getShippingValueAmount(),
                    'currency' => $shipment->getCustoms()->getShippingValueCurrency(),
                ],
                'total_tax'      => $shipment->getCustoms()->getTotalTaxAmount() === null ? null : [
                    'amount'   => $shipment->getCustoms()->getTotalTaxAmount(),
                    'currency' => $shipment->getCustoms()->getTotalTaxCurrency(),
                ],
                'total_duty'     => $shipment->getCustoms()->getTotalDutyAmount() === null ? null : [
                    'amount'   => $shipment->getCustoms()->getTotalDutyAmount(),
                    'currency' => $shipment->getCustoms()->getTotalDutyCurrency(),
                ],
            ]),
            'final_mile_carrier'                   => $shipment->getFinalMileCarrier() ? [
                'tracking_url'  => $shipment->getFinalMileCarrier()->getUrl(),
                'name'          => $shipment->getFinalMileCarrier()->getName(),
                'tracking_code' => $shipment->getFinalMileCarrier()->getTrackingCode(),
            ] : null,
        ]);
    }

    /**
     * @param Address $address
     * @return array
     */
    private function transformAddress(Address $address): array
    {
        return array_filter([
            'street_1'             => $address->getStreet1(),
            'street_2'             => $address->getStreet2(),
            'street_number'        => $address->getStreetNumber(),
            'street_number_suffix' => $address->getStreetNumberSuffix(),
            'postal_code'          => $address->getPostalCode(),
            'city'                 => $address->getCity(),
            'region_code'          => $address->getRegionCode(),
            'state_code'           => $address->getStateCode(),
            'country_code'         => $address->getCountryCode(),
            'first_name'           => $address->getFirstName(),
            'last_name'            => $address->getLastName(),
            'company'              => $address->getCompany(),
            'email'                => $address->getEmail(),
            'phone_number'         => $address->getPhoneNumber(),
        ]);
    }

    /**
     * @param Shipment $shipment
     * @return array[]
     */
    public function getRelationships($shipment): array
    {
        $shipmentIDs = $shipment->getConsolidationShipments()->map(fn (array $shipment) => $shipment['id'])->all();

        return array_filter([
            'consolidated_shipments' => $this->transformRelationshipForIdentifiers($shipmentIDs, 'shipments'),
        ]);
    }

    /**
     * @param Shipment $shipment
     * @throws ModelTypeException
     */
    protected function validateModel($shipment): void
    {
        if (!$shipment instanceof Shipment) {
            throw new ModelTypeException($shipment, 'shipments');
        }
    }
}
