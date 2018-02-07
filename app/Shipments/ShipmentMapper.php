<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Shipments;

use Com\Tecnick\Barcode\Barcode;
use MyParcelCom\JsonApi\Interfaces\MapperInterface;
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

        if ($shipment->getPhysicalProperties() === null) {
            $shipment->setPhysicalProperties(new PhysicalProperties());
        }
        if (isset($attributes['physical_properties']['weight'])) {
            $shipment->getPhysicalProperties()->setWeight((int)$attributes['physical_properties']['weight']);
        }
        if (isset($attributes['physical_properties']['width'])) {
            $shipment->getPhysicalProperties()->setWidth((int)$attributes['physical_properties']['width']);
        }
        if (isset($attributes['physical_properties']['height'])) {
            $shipment->getPhysicalProperties()->setHeight((int)$attributes['physical_properties']['height']);
        }
        if (isset($attributes['physical_properties']['length'])) {
            $shipment->getPhysicalProperties()->setLength((int)$attributes['physical_properties']['length']);
        }
        if (isset($attributes['physical_properties']['volume'])) {
            $shipment->getPhysicalProperties()->setVolume((float)$attributes['physical_properties']['volume']);
        }

        if (isset($attributes['options'])) {
            $this->mapOptions($attributes['options'], $shipment);
        }

        // If no insurance is set, fall back to defaults.
        $shipment->setInsuranceAmount((int)($attributes['insurance']['amount'] ?? self::DEFAULT_INSURANCE_AMOUNT));
        $shipment->setInsuranceCurrency($attributes['insurance']['currency'] ?? self::DEFAULT_CURRENCY);

        // Map customs information.
        if (!empty($attributes['customs'])) {
            $this->mapCustoms($attributes['customs'], $shipment);
        }

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
     * @param array    $data
     * @param Shipment $shipment
     * @return $this
     */
    protected function mapCustoms(array $data, Shipment $shipment): self
    {
        $customs = $shipment->getCustoms() ?? new Customs();

        if (isset($data['content_type'])) {
            $customs->setContentType($data['content_type']);
        }
        if (isset($data['invoice_number'])) {
            $customs->setInvoiceNumber($data['invoice_number']);
        }
        if (isset($data['non_delivery'])) {
            $customs->setNonDelivery($data['non_delivery']);
        }
        if (isset($data['incoterm'])) {
            $customs->setIncoterm($data['incoterm']);
        }

        if (!empty($data['items'])) {
            $customs->setItems(
                array_map([$this, 'mapCustomsItem'], $data['items'])
            );
        }

        $shipment->setCustoms($customs);

        return $this;
    }

    /**
     * @param array $data
     * @return CustomsItem
     */
    protected function mapCustomsItem(array $data): CustomsItem
    {
        $item = new CustomsItem();

        if (isset($data['sku'])) {
            $item->setSku($data['sku']);
        }
        if (isset($data['description'])) {
            $item->setDescription($data['description']);
        }
        if (isset($data['item_value'])) {
            $item->setItemValueAmount($data['item_value']['amount']);
            $item->setItemValueCurrency($data['item_value']['currency']);
        }
        if (isset($data['quantity'])) {
            $item->setQuantity($data['quantity']);
        }
        if (isset($data['hs_code'])) {
            $item->setHsCode($data['hs_code']);
        }
        if (isset($data['origin_country_code'])) {
            $item->setOriginCountryCode($data['origin_country_code']);
        }

        return $item;
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
