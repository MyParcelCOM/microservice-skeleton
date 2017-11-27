<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Shipments;

use MyParcelCom\Microservice\PickUpDropOffLocations\Address;
use MyParcelCom\Transformers\AbstractTransformer;
use MyParcelCom\Transformers\TransformerException;

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
            'recipient_address'   => $this->transformAddress($shipment->getRecipientAddress()),
            'sender_address'      => $this->transformAddress($shipment->getSenderAddress()),
            'pickup_location'     => $shipment->getPickupLocationCode() === null ? null : [
                'code'    => $shipment->getPickupLocationCode(),
                'address' => $this->transformAddress($shipment->getPickupLocationAddress()),
            ],
            'description'         => $shipment->getDescription(),
            'price'               => [
                'amount'   => $shipment->getPriceAmount(),
                'currency' => $shipment->getPriceCurrency(),
            ],
            'insurance'           => [
                'amount'   => $shipment->getInsuranceAmount(),
                'currency' => $shipment->getInsuranceCurrency(),
            ],
            'barcode'             => $shipment->getBarcode(),
            'weight'              => $shipment->getWeight(),
            'service'             => [
                'code' => $shipment->getService()->getCode(),
                'name' => $shipment->getService()->getName(),
            ],
            'options'             => array_map(function (Option $option) {
                return [
                    'code' => $option->getCode(),
                    'name' => $option->getName(),
                ];
            }, $shipment->getOptions()),
            'physical_properties' => $shipment->getPhysicalProperties() === null ? null : [
                'height' => $shipment->getPhysicalProperties()->getHeight(),
                'width'  => $shipment->getPhysicalProperties()->getWidth(),
                'length' => $shipment->getPhysicalProperties()->getLength(),
                'volume' => $shipment->getPhysicalProperties()->getVolume(),
                'weight' => $shipment->getPhysicalProperties()->getWeight(),
            ],
            'files'               => array_map(function (File $file) {
                return [
                    'resource_type' => $file->getType(),
                    'mime_type'     => $file->getMimeType(),
                    'extension'     => $file->getExtension(),
                    'data'          => $file->getData(),
                ];
            }, $shipment->getFiles()),
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
     * @throws TransformerException
     */
    protected function validateModel($shipment): void
    {
        if (!$shipment instanceof Shipment) {
            throw new TransformerException(
                'Invalid model supplied, expected instance of `Shipment`, got: ' . get_class($shipment)
            );
        }
    }
}
