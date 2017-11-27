<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Shipments;

use Com\Tecnick\Barcode\Barcode;
use MyParcelCom\Common\Contracts\MapperInterface;
use MyParcelCom\Microservice\PickUpDropOffLocations\Address;

class ShipmentMapper implements MapperInterface
{
    protected const DEFAULT_INSURANCE_AMOUNT = 0;
    protected const DEFAULT_CURRENCY = 'EUR';

    protected const PRINTCODE_WIDTH = 299;
    protected const PRINTCODE_HEIGHT = 95;

    /** @var Barcode */
    protected $barcodeGenerator;

    /**
     * Maps given data to given model and returns the model.
     *
     * @param array    $data
     * @param Shipment $shipment
     * @return Shipment
     */
    public function map($data, $shipment): Shipment
    {
        $attributes = $data['attributes'];

        // Map addresses.
        $shipment->setRecipientAddress(
            $this->mapAddress($attributes['recipient_address'], new Address())
        );

        $shipment->setSenderAddress(
            $this->mapAddress($attributes['sender_address'], new Address())
        );

        if (isset($attributes['pickup_location'])) {
            $shipment->setPickupLocationAddress(
                $this->mapAddress($attributes['pickup_location']['address'], new Address())
            );
            $shipment->setPickupLocationCode($attributes['pickup_location']['code']);
        }

        // Map service.
        $this->mapService($attributes['service'], $shipment);
        $shipment->setWeight((int)$attributes['weight']);

        if (isset($attributes['options'])) {
            $this->mapOptions($attributes['options'], $shipment);
        }

        // If no insurance is set, fall back to defaults.
        $shipment->setInsuranceAmount((int)($attributes['insurance']['amount'] ?? self::DEFAULT_INSURANCE_AMOUNT));
        $shipment->setInsuranceCurrency($attributes['insurance']['currency'] ?? self::DEFAULT_CURRENCY);

        // Map optional data.
        if (isset($attributes['description'])) {
            $shipment->setDescription($attributes['description']);
        }

        return $shipment;
    }

    /**
     * @param array    $data
     * @param Shipment $shipment
     * @return $this
     */
    protected function mapService(array $data, Shipment $shipment): self
    {
        $service = (new Service())
            ->setCode($data['code']);

        if (isset($data['name'])) {
            $service->setName($data['name']);
        }

        $shipment->setService($service);

        return $this;
    }

    /**
     * @param array    $options
     * @param Shipment $shipment
     * @return $this
     */
    protected function mapOptions(array $options, Shipment $shipment): self
    {
        array_walk($options, function (array $optionData) use ($shipment) {
            $option = (new Option())
                ->setCode($optionData['code']);

            if (isset($optionData['name'])) {
                $option->setName($optionData['name']);
            }

            $shipment->addOption($option);
        });

        return $this;
    }

    /**
     * @param array   $data
     * @param Address $address
     * @return Address
     */
    protected function mapAddress(array $data, Address $address): Address
    {
        if (isset($data['street_1'])) {
            $address->setStreet1((string)$data['street_1']);
        }
        if (isset($data['street_2'])) {
            $address->setStreet2($data['street_2']);
        }
        if (isset($data['street_number'])) {
            $address->setStreetNumber($data['street_number']);
        }
        if (isset($data['street_number_suffix'])) {
            $address->setStreetNumberSuffix($data['street_number_suffix']);
        }
        if (isset($data['postal_code'])) {
            $address->setPostalCode($data['postal_code']);
        }
        if (isset($data['city'])) {
            $address->setCity($data['city']);
        }
        if (isset($data['region_code'])) {
            $address->setRegionCode($data['region_code']);
        }
        if (isset($data['country_code'])) {
            $address->setCountryCode($data['country_code']);
        }
        if (isset($data['first_name'])) {
            $address->setFirstName($data['first_name']);
        }
        if (isset($data['last_name'])) {
            $address->setLastName($data['last_name']);
        }
        if (isset($data['company'])) {
            $address->setCompany($data['company']);
        }
        if (isset($data['email'])) {
            $address->setEmail($data['email']);
        }
        if (isset($data['phone_number'])) {
            $address->setPhoneNumber($data['phone_number']);
        }

        return $address;
    }

    /**
     * @param Barcode $barcodeGenerator
     * @return $this
     */
    public function setBarcodeGenerator(Barcode $barcodeGenerator): self
    {
        $this->barcodeGenerator = $barcodeGenerator;

        return $this;
    }
}
