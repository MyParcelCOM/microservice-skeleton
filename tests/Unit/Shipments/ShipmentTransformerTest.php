<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Shipments;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Collection;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use MyParcelCom\FinalMileCarrier\FinalMileCarrier;
use MyParcelCom\JsonApi\Exceptions\ModelTypeException;
use MyParcelCom\JsonApi\Transformers\TransformerFactory;
use MyParcelCom\Microservice\PickUpDropOffLocations\Address;
use MyParcelCom\Microservice\Shipments\Customs;
use MyParcelCom\Microservice\Shipments\Option;
use MyParcelCom\Microservice\Shipments\PhysicalProperties;
use MyParcelCom\Microservice\Shipments\Service;
use MyParcelCom\Microservice\Shipments\Shipment;
use MyParcelCom\Microservice\Shipments\ShipmentItem;
use MyParcelCom\Microservice\Shipments\ShipmentTransformer;
use PHPUnit\Framework\TestCase;
use stdClass;

class ShipmentTransformerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var ShipmentTransformer */
    private $shipmentTransformer;

    /** @var Shipment */
    private $shipment;

    /** @var Shipment */
    private $minimalShipment;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var TransformerFactory $transformerFactory */
        $transformerFactory = Mockery::mock(TransformerFactory::class);

        $address = Mockery::mock(Address::class, [
            'getStreet1'            => 'First Street',
            'getStreet2'            => 'Second Street',
            'getStreetNumber'       => 69,
            'getStreetNumberSuffix' => 'x',
            'getPostalCode'         => '1337OP',
            'getCity'               => 'Felicity',
            'getRegionCode'         => 'NH',
            'getStateCode'          => 'XXX',
            'getCountryCode'        => 'NL',
            'getFirstName'          => 'Jane',
            'getLastName'           => 'Doe',
            'getCompany'            => 'Experts Exchange',
            'getEmail'              => 'john@expertsexchange.com',
            'getPhoneNumber'        => '1337-9001',
        ]);

        $service = Mockery::mock(Service::class, [
            'getCode' => 'nl300',
            'getName' => 'noname',
        ]);

        $physicalProperties = Mockery::mock(PhysicalProperties::class, [
            'getHeight'           => 1,
            'getWidth'            => 2,
            'getLength'           => 3,
            'getVolume'           => 4.4,
            'getWeight'           => 5,
            'getVolumetricWeight' => 6,
        ]);

        $option = Mockery::mock(Option::class, [
            'getCode'   => 'somecode',
            'getName'   => 'plx name me',
            'getValues' => null,
        ]);

        $shipmentItem = Mockery::mock(ShipmentItem::class, [
            'getSku'               => '01284ASD',
            'getDescription'       => 'priceless Ming vase from some dynasty',
            'getImageUrl'          => '//get.rich',
            'getQuantity'          => 12,
            'getHsCode'            => '9801.00.60',
            'getOriginCountryCode' => 'CN',
            'getItemValueAmount'   => 100000000,
            'getItemValueCurrency' => 'USD',
            'getTaxAmount'         => 8008,
            'getTaxCurrency'       => 'EUR',
            'getDutyAmount'        => 707,
            'getDutyCurrency'      => 'USD',
            'getVatPercentage'     => 30,
        ]);

        $customs = Mockery::mock(Customs::class, [
            'getContentType'           => Customs::CONTENT_TYPE_DOCUMENTS,
            'getInvoiceNumber'         => 'V01C3',
            'getNonDelivery'           => Customs::NON_DELIVERY_ABANDON,
            'getIncoterm'              => Customs::INCOTERM_DELIVERED_AT_PLACE,
            'getShippingValueAmount'   => 6456,
            'getShippingValueCurrency' => 'EUR',
            'getTotalTaxAmount'        => 58008,
            'getTotalTaxCurrency'      => 'EUR',
            'getTotalDutyAmount'       => 737,
            'getTotalDutyCurrency'     => 'USD',
        ]);

        $this->shipmentTransformer = (new ShipmentTransformer($transformerFactory))
            ->setUrlGenerator(Mockery::mock(UrlGenerator::class, ['route' => 'url']));
        $this->shipment = Mockery::mock(Shipment::class, [
            'getId'                                => 'shipment-id',
            'getRecipientAddress'                  => $address,
            'getSenderAddress'                     => $address,
            'getSenderTaxNumber'                   => 'G666666-66',
            'getSenderTaxIdentificationNumbers'    => [
                [
                    'country_code' => 'GB',
                    'number'       => 'XI123456789',
                    'type'         => 'eori',
                ],
            ],
            'getReturnAddress'                     => $address,
            'getRecipientTaxNumber'                => 'H111111-11',
            'getRecipientTaxIdentificationNumbers' => [
                [
                    'country_code' => 'NL',
                    'number'       => 'YI123456789',
                    'type'         => 'eori',
                ],
            ],
            'getTaxIdentificationNumbers'          => [
                [
                    'country_code' => 'GB',
                    'number'       => 'XI123456789',
                    'type'         => 'eori',
                ],
            ],
            'getPickupLocationCode'                => 'aaaa',
            'getPickupLocationAddress'             => $address,
            'getDescription'                       => 'descending ription',
            'getTotalValueAmount'                  => 42,
            'getTotalValueCurrency'                => 'EUR',
            'getBarcode'                           => '3SBARCODE',
            'getTrackingCode'                      => 'TR4CK1NGC0D3',
            'getTrackingUrl'                       => 'https://track.me/TR4CK1NGC0D3',
            'getWeight'                            => 789,
            'getService'                           => $service,
            'getOptions'                           => [$option],
            'getPhysicalProperties'                => $physicalProperties,
            'getFiles'                             => [],
            'getCustoms'                           => $customs,
            'getItems'                             => [$shipmentItem],
            'getMyparcelcomShipmentId'             => 'bbacd0c7-9ec5-42df-9870-443b8e1a7155',
            'getFinalMileCarrier'                  => Mockery::mock(FinalMileCarrier::class, [
                'getName' => 'Final-Track',
                'getUrl'  => 'https://finaltrack.me/details/TR4CK1NGC0D3',
            ]),
            'getConsolidationShipments'            => new Collection([
                Mockery::mock(Shipment::class, [
                    'getId' => 'con-1',
                ]),
                Mockery::mock(Shipment::class, [
                    'getId' => 'con-2',
                ]),
            ]),
        ]);

        $this->minimalShipment = Mockery::mock(Shipment::class, [
            'getId'                                => 'shipment-id',
            'getRecipientAddress'                  => $address,
            'getSenderAddress'                     => $address,
            'getSenderTaxNumber'                   => null,
            'getSenderTaxIdentificationNumbers'    => [],
            'getReturnAddress'                     => $address,
            'getRecipientTaxNumber'                => null,
            'getRecipientTaxIdentificationNumbers' => [],
            'getTaxIdentificationNumbers'          => [],
            'getPickupLocationCode'                => null,
            'getPickupLocationAddress'             => null,
            'getDescription'                       => null,
            'getTotalValueAmount'                  => null,
            'getTotalValueCurrency'                => null,
            'getBarcode'                           => null,
            'getTrackingCode'                      => null,
            'getTrackingUrl'                       => null,
            'getWeight'                            => 789,
            'getService'                           => $service,
            'getOptions'                           => [],
            'getPhysicalProperties'                => null,
            'getFiles'                             => [],
            'getCustoms'                           => null,
            'getItems'                             => [],
            'getMyparcelcomShipmentId'             => 'bbacd0c7-9ec5-42df-9870-443b8e1a7155',
            'getFinalMileCarrier'                  => null,
            'getConsolidationShipments'            => new Collection(),
        ]);
    }

    /** @test */
    public function testGetId()
    {
        $this->assertEquals(
            'shipment-id',
            $this->shipmentTransformer->getId($this->shipment),
            'Failed getting model id.'
        );
    }

    /** @test */
    public function testGetType()
    {
        $this->assertEquals(
            'shipments',
            $this->shipmentTransformer->getType()
        );
    }

    /** @test */
    public function testGetAttributes()
    {
        $this->assertEquals([
            'recipient_address'                    => [
                'street_1'             => 'First Street',
                'street_2'             => 'Second Street',
                'street_number'        => 69,
                'street_number_suffix' => 'x',
                'postal_code'          => '1337OP',
                'city'                 => 'Felicity',
                'region_code'          => 'NH',
                'state_code'           => 'XXX',
                'country_code'         => 'NL',
                'first_name'           => 'Jane',
                'last_name'            => 'Doe',
                'company'              => 'Experts Exchange',
                'email'                => 'john@expertsexchange.com',
                'phone_number'         => '1337-9001',
            ],
            'recipient_tax_number'                 => 'H111111-11',
            'recipient_tax_identification_numbers' => [
                [
                    'country_code' => 'NL',
                    'number'       => 'YI123456789',
                    'type'         => 'eori',
                ],
            ],
            'sender_address'                       => [
                'street_1'             => 'First Street',
                'street_2'             => 'Second Street',
                'street_number'        => 69,
                'street_number_suffix' => 'x',
                'postal_code'          => '1337OP',
                'city'                 => 'Felicity',
                'region_code'          => 'NH',
                'state_code'           => 'XXX',
                'country_code'         => 'NL',
                'first_name'           => 'Jane',
                'last_name'            => 'Doe',
                'company'              => 'Experts Exchange',
                'email'                => 'john@expertsexchange.com',
                'phone_number'         => '1337-9001',
            ],
            'sender_tax_number'                    => 'G666666-66',
            'sender_tax_identification_numbers'    => [
                [
                    'country_code' => 'GB',
                    'number'       => 'XI123456789',
                    'type'         => 'eori',
                ],
            ],
            'tax_identification_numbers'           => [
                [
                    'country_code' => 'GB',
                    'number'       => 'XI123456789',
                    'type'         => 'eori',
                ],
            ],
            'return_address'                       => [
                'street_1'             => 'First Street',
                'street_2'             => 'Second Street',
                'street_number'        => 69,
                'street_number_suffix' => 'x',
                'postal_code'          => '1337OP',
                'city'                 => 'Felicity',
                'region_code'          => 'NH',
                'state_code'           => 'XXX',
                'country_code'         => 'NL',
                'first_name'           => 'Jane',
                'last_name'            => 'Doe',
                'company'              => 'Experts Exchange',
                'email'                => 'john@expertsexchange.com',
                'phone_number'         => '1337-9001',
            ],
            'pickup_location'                      => [
                'code'    => 'aaaa',
                'address' => [
                    'street_1'             => 'First Street',
                    'street_2'             => 'Second Street',
                    'street_number'        => 69,
                    'street_number_suffix' => 'x',
                    'postal_code'          => '1337OP',
                    'city'                 => 'Felicity',
                    'region_code'          => 'NH',
                    'state_code'           => 'XXX',
                    'country_code'         => 'NL',
                    'first_name'           => 'Jane',
                    'last_name'            => 'Doe',
                    'company'              => 'Experts Exchange',
                    'email'                => 'john@expertsexchange.com',
                    'phone_number'         => '1337-9001',
                ],
            ],
            'description'                          => 'descending ription',
            'total_value'                          => [
                'amount'   => 42,
                'currency' => 'EUR',
            ],
            'barcode'                              => '3SBARCODE',
            'tracking_code'                        => 'TR4CK1NGC0D3',
            'tracking_url'                         => 'https://track.me/TR4CK1NGC0D3',
            'service'                              => [
                'code' => 'nl300',
                'name' => 'noname',
            ],
            'physical_properties'                  => [
                'height'            => 1,
                'width'             => 2,
                'length'            => 3,
                'volume'            => 4.4,
                'weight'            => 5,
                'volumetric_weight' => 6,
            ],
            'options'                              => [
                [
                    'name' => 'plx name me',
                    'code' => 'somecode',
                ],
            ],
            'items'                                => [
                [
                    'sku'                 => '01284ASD',
                    'description'         => 'priceless Ming vase from some dynasty',
                    'image_url'           => '//get.rich',
                    'quantity'            => 12,
                    'hs_code'             => '9801.00.60',
                    'origin_country_code' => 'CN',
                    'item_value'          => [
                        'amount'   => 100000000,
                        'currency' => 'USD',
                    ],
                    'tax'                 => [
                        'amount'   => 8008,
                        'currency' => 'EUR',
                    ],
                    'duty'                => [
                        'amount'   => 707,
                        'currency' => 'USD',
                    ],
                    'vat_percentage'      => 30,
                ],
            ],
            'customs'                              => [
                'content_type'   => Customs::CONTENT_TYPE_DOCUMENTS,
                'invoice_number' => 'V01C3',
                'incoterm'       => Customs::INCOTERM_DELIVERED_AT_PLACE,
                'non_delivery'   => Customs::NON_DELIVERY_ABANDON,
                'shipping_value' => [
                    'amount'   => 6456,
                    'currency' => 'EUR',
                ],
                'total_tax'      => [
                    'amount'   => 58008,
                    'currency' => 'EUR',
                ],
                'total_duty'     => [
                    'amount'   => 737,
                    'currency' => 'USD',
                ],
            ],
            'myparcelcom_shipment_id'              => 'bbacd0c7-9ec5-42df-9870-443b8e1a7155',
            'final_mile_carrier'                   => [
                'name'         => 'Final-Track',
                'tracking_url' => 'https://finaltrack.me/details/TR4CK1NGC0D3',
            ],
        ], $this->shipmentTransformer->getAttributes($this->shipment));
    }

    /** @test */
    public function testTransform()
    {
        $this->assertEquals(
            [
                'id'            => 'shipment-id',
                'type'          => 'shipments',
                'attributes'    => [
                    'recipient_address'                    => [
                        'street_1'             => 'First Street',
                        'street_2'             => 'Second Street',
                        'street_number'        => 69,
                        'street_number_suffix' => 'x',
                        'postal_code'          => '1337OP',
                        'city'                 => 'Felicity',
                        'region_code'          => 'NH',
                        'state_code'           => 'XXX',
                        'country_code'         => 'NL',
                        'first_name'           => 'Jane',
                        'last_name'            => 'Doe',
                        'company'              => 'Experts Exchange',
                        'email'                => 'john@expertsexchange.com',
                        'phone_number'         => '1337-9001',
                    ],
                    'recipient_tax_number'                 => 'H111111-11',
                    'recipient_tax_identification_numbers' => [
                        [
                            'country_code' => 'NL',
                            'number'       => 'YI123456789',
                            'type'         => 'eori',
                        ],
                    ],
                    'return_address'                       => [
                        'street_1'             => 'First Street',
                        'street_2'             => 'Second Street',
                        'street_number'        => 69,
                        'street_number_suffix' => 'x',
                        'postal_code'          => '1337OP',
                        'city'                 => 'Felicity',
                        'region_code'          => 'NH',
                        'state_code'           => 'XXX',
                        'country_code'         => 'NL',
                        'first_name'           => 'Jane',
                        'last_name'            => 'Doe',
                        'company'              => 'Experts Exchange',
                        'email'                => 'john@expertsexchange.com',
                        'phone_number'         => '1337-9001',
                    ],
                    'sender_tax_number'                    => 'G666666-66',
                    'sender_tax_identification_numbers'    => [
                        [
                            'country_code' => 'GB',
                            'number'       => 'XI123456789',
                            'type'         => 'eori',
                        ],
                    ],
                    'tax_identification_numbers'           => [
                        [
                            'country_code' => 'GB',
                            'number'       => 'XI123456789',
                            'type'         => 'eori',
                        ],
                    ],
                    'sender_address'                       => [
                        'street_1'             => 'First Street',
                        'street_2'             => 'Second Street',
                        'street_number'        => 69,
                        'street_number_suffix' => 'x',
                        'postal_code'          => '1337OP',
                        'city'                 => 'Felicity',
                        'region_code'          => 'NH',
                        'state_code'           => 'XXX',
                        'country_code'         => 'NL',
                        'first_name'           => 'Jane',
                        'last_name'            => 'Doe',
                        'company'              => 'Experts Exchange',
                        'email'                => 'john@expertsexchange.com',
                        'phone_number'         => '1337-9001',
                    ],
                    'pickup_location'                      => [
                        'code'    => 'aaaa',
                        'address' => [
                            'street_1'             => 'First Street',
                            'street_2'             => 'Second Street',
                            'street_number'        => 69,
                            'street_number_suffix' => 'x',
                            'postal_code'          => '1337OP',
                            'city'                 => 'Felicity',
                            'region_code'          => 'NH',
                            'state_code'           => 'XXX',
                            'country_code'         => 'NL',
                            'first_name'           => 'Jane',
                            'last_name'            => 'Doe',
                            'company'              => 'Experts Exchange',
                            'email'                => 'john@expertsexchange.com',
                            'phone_number'         => '1337-9001',
                        ],
                    ],
                    'description'                          => 'descending ription',
                    'total_value'                          => [
                        'amount'   => 42,
                        'currency' => 'EUR',
                    ],
                    'barcode'                              => '3SBARCODE',
                    'service'                              => [
                        'code' => 'nl300',
                        'name' => 'noname',
                    ],
                    'physical_properties'                  => [
                        'height'            => 1,
                        'width'             => 2,
                        'length'            => 3,
                        'volume'            => 4.4,
                        'weight'            => 5,
                        'volumetric_weight' => 6,
                    ],
                    'options'                              => [
                        [
                            'name' => 'plx name me',
                            'code' => 'somecode',
                        ],
                    ],
                    'items'                                => [
                        [
                            'sku'                 => '01284ASD',
                            'description'         => 'priceless Ming vase from some dynasty',
                            'image_url'           => '//get.rich',
                            'quantity'            => 12,
                            'hs_code'             => '9801.00.60',
                            'origin_country_code' => 'CN',
                            'item_value'          => [
                                'amount'   => 100000000,
                                'currency' => 'USD',
                            ],
                            'tax'                 => [
                                'amount'   => 8008,
                                'currency' => 'EUR',
                            ],
                            'duty'                => [
                                'amount'   => 707,
                                'currency' => 'USD',
                            ],
                            'vat_percentage'      => 30,
                        ],
                    ],
                    'customs'                              => [
                        'content_type'   => Customs::CONTENT_TYPE_DOCUMENTS,
                        'invoice_number' => 'V01C3',
                        'incoterm'       => Customs::INCOTERM_DELIVERED_AT_PLACE,
                        'non_delivery'   => Customs::NON_DELIVERY_ABANDON,
                        'shipping_value' => [
                            'amount'   => 6456,
                            'currency' => 'EUR',
                        ],
                        'total_tax'      => [
                            'amount'   => 58008,
                            'currency' => 'EUR',
                        ],
                        'total_duty'     => [
                            'amount'   => 737,
                            'currency' => 'USD',
                        ],
                    ],
                    'myparcelcom_shipment_id'              => 'bbacd0c7-9ec5-42df-9870-443b8e1a7155',
                    'tracking_code'                        => 'TR4CK1NGC0D3',
                    'tracking_url'                         => 'https://track.me/TR4CK1NGC0D3',
                    'final_mile_carrier'                   => [
                        'name'         => 'Final-Track',
                        'tracking_url' => 'https://finaltrack.me/details/TR4CK1NGC0D3',
                    ],
                ],
                'relationships' => [
                    'consolidated_shipments' => [
                        'data' => [
                            [
                                'type' => 'shipments',
                                'id'   => 'con-1',
                            ],
                            [
                                'type' => 'shipments',
                                'id'   => 'con-2',
                            ],
                        ],
                    ],
                ],
            ],
            $this->shipmentTransformer->transform($this->shipment)
        );
    }

    /** @test */
    public function testTransformMinimalShipment()
    {
        $this->assertEquals(
            [
                'id'         => 'shipment-id',
                'type'       => 'shipments',
                'attributes' => [
                    'recipient_address'       => [
                        'street_1'             => 'First Street',
                        'street_2'             => 'Second Street',
                        'street_number'        => 69,
                        'street_number_suffix' => 'x',
                        'postal_code'          => '1337OP',
                        'city'                 => 'Felicity',
                        'region_code'          => 'NH',
                        'state_code'           => 'XXX',
                        'country_code'         => 'NL',
                        'first_name'           => 'Jane',
                        'last_name'            => 'Doe',
                        'company'              => 'Experts Exchange',
                        'email'                => 'john@expertsexchange.com',
                        'phone_number'         => '1337-9001',
                    ],
                    'return_address'          => [
                        'street_1'             => 'First Street',
                        'street_2'             => 'Second Street',
                        'street_number'        => 69,
                        'street_number_suffix' => 'x',
                        'postal_code'          => '1337OP',
                        'city'                 => 'Felicity',
                        'region_code'          => 'NH',
                        'state_code'           => 'XXX',
                        'country_code'         => 'NL',
                        'first_name'           => 'Jane',
                        'last_name'            => 'Doe',
                        'company'              => 'Experts Exchange',
                        'email'                => 'john@expertsexchange.com',
                        'phone_number'         => '1337-9001',
                    ],
                    'sender_address'          => [
                        'street_1'             => 'First Street',
                        'street_2'             => 'Second Street',
                        'street_number'        => 69,
                        'street_number_suffix' => 'x',
                        'postal_code'          => '1337OP',
                        'city'                 => 'Felicity',
                        'region_code'          => 'NH',
                        'state_code'           => 'XXX',
                        'country_code'         => 'NL',
                        'first_name'           => 'Jane',
                        'last_name'            => 'Doe',
                        'company'              => 'Experts Exchange',
                        'email'                => 'john@expertsexchange.com',
                        'phone_number'         => '1337-9001',
                    ],
                    'service'                 => [
                        'code' => 'nl300',
                        'name' => 'noname',
                    ],
                    'myparcelcom_shipment_id' => 'bbacd0c7-9ec5-42df-9870-443b8e1a7155',
                ],
            ],
            $this->shipmentTransformer->transform($this->minimalShipment)
        );
    }

    /** @test */
    public function testTransformInvalidModel()
    {
        $this->expectException(ModelTypeException::class);
        $this->shipmentTransformer->transform(new stdClass());
    }
}
