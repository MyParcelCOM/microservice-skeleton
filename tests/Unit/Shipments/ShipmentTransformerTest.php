<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Shipments;

use Mockery;
use MyParcelCom\Common\Contracts\UrlGeneratorInterface;
use MyParcelCom\Microservice\PickUpDropOffLocations\Address;
use MyParcelCom\Microservice\Shipments\Customs;
use MyParcelCom\Microservice\Shipments\CustomsItem;
use MyParcelCom\Microservice\Shipments\Option;
use MyParcelCom\Microservice\Shipments\PhysicalProperties;
use MyParcelCom\Microservice\Shipments\Service;
use MyParcelCom\Microservice\Shipments\Shipment;
use MyParcelCom\Microservice\Shipments\ShipmentTransformer;
use MyParcelCom\Transformers\TransformerException;
use MyParcelCom\Transformers\TransformerFactory;
use PHPUnit\Framework\TestCase;

class ShipmentTransformerTest extends TestCase
{
    /** @var ShipmentTransformer */
    private $shipmentTransformer;

    /** @var Shipment */
    private $shipment;

    public function setUp()
    {
        parent::setUp();

        $urlGenerator = Mockery::mock(UrlGeneratorInterface::class, ['route' => 'url']);
        $transformerFactory = Mockery::mock(TransformerFactory::class);

        $address = Mockery::mock(Address::class, [
            'getStreet1'            => 'First Street',
            'getStreet2'            => 'Second Street',
            'getStreetNumber'       => 69,
            'getStreetNumberSuffix' => 'x',
            'getPostalCode'         => '1337OP',
            'getCity'               => 'Felicity',
            'getRegionCode'         => 'NH',
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
            'getHeight' => 1,
            'getWidth'  => 2,
            'getLength' => 3,
            'getVolume' => 4,
            'getWeight' => 5,
        ]);

        $option = Mockery::mock(Option::class, [
            'getCode' => 'somecode',
            'getName' => 'plx name me',
        ]);

        $customsItem = Mockery::mock(CustomsItem::class, [
            'getSku'               => '01284ASD',
            'getDescription'       => 'priceless Ming vase from some dynasty',
            'getQuantity'          => 12,
            'getHsCode'            => '9801.00.60',
            'getOriginCountryCode' => 'CN',
            'getItemValueAmount'   => 100000000,
            'getItemValueCurrency' => 'USD',
        ]);

        $customs = Mockery::mock(Customs::class, [
            'getContentType'   => Customs::CONTENT_TYPE_DOCUMENTS,
            'getInvoiceNumber' => 'V01C3',
            'getNonDelivery'   => Customs::NON_DELIVERY_ABANDON,
            'getIncoterm'      => Customs::INCOTERM_DUTY_DELIVERY_UNPAID,
            'getItems'         => [$customsItem],
        ]);

        $this->shipmentTransformer = new ShipmentTransformer($urlGenerator, $transformerFactory);
        $this->shipment = Mockery::mock(Shipment::class, [
            'getId'                    => 'shipment-id',
            'getRecipientAddress'      => $address,
            'getSenderAddress'         => $address,
            'getPickupLocationCode'    => 'aaaa',
            'getPickupLocationAddress' => $address,
            'getDescription'           => 'descending ription',
            'getPriceAmount'           => 123,
            'getPriceCurrency'         => 'EUR',
            'getInsuranceAmount'       => 456,
            'getInsuranceCurrency'     => 'EUR',
            'getBarcode'               => '3SBARCODE',
            'getWeight'                => 789,
            'getService'               => $service,
            'getOptions'               => [$option],
            'getPhysicalProperties'    => $physicalProperties,
            'getFiles'                 => [],
            'getCustoms'               => $customs,
        ]);
    }

    public function tearDown()
    {
        parent::tearDown();

        Mockery::close();
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
            'recipient_address'   => [
                'street_1'             => 'First Street',
                'street_2'             => 'Second Street',
                'street_number'        => 69,
                'street_number_suffix' => 'x',
                'postal_code'          => '1337OP',
                'city'                 => 'Felicity',
                'region_code'          => 'NH',
                'country_code'         => 'NL',
                'first_name'           => 'Jane',
                'last_name'            => 'Doe',
                'company'              => 'Experts Exchange',
                'email'                => 'john@expertsexchange.com',
                'phone_number'         => '1337-9001',
            ],
            'sender_address'      => [
                'street_1'             => 'First Street',
                'street_2'             => 'Second Street',
                'street_number'        => 69,
                'street_number_suffix' => 'x',
                'postal_code'          => '1337OP',
                'city'                 => 'Felicity',
                'region_code'          => 'NH',
                'country_code'         => 'NL',
                'first_name'           => 'Jane',
                'last_name'            => 'Doe',
                'company'              => 'Experts Exchange',
                'email'                => 'john@expertsexchange.com',
                'phone_number'         => '1337-9001',
            ],
            'pickup_location'     => [
                'code'    => 'aaaa',
                'address' => [
                    'street_1'             => 'First Street',
                    'street_2'             => 'Second Street',
                    'street_number'        => 69,
                    'street_number_suffix' => 'x',
                    'postal_code'          => '1337OP',
                    'city'                 => 'Felicity',
                    'region_code'          => 'NH',
                    'country_code'         => 'NL',
                    'first_name'           => 'Jane',
                    'last_name'            => 'Doe',
                    'company'              => 'Experts Exchange',
                    'email'                => 'john@expertsexchange.com',
                    'phone_number'         => '1337-9001',
                ],
            ],
            'description'         => 'descending ription',
            'price'               => [
                'amount'   => 123,
                'currency' => 'EUR',
            ],
            'insurance'           => [
                'amount'   => 456,
                'currency' => 'EUR',
            ],
            'barcode'             => '3SBARCODE',
            'weight'              => 789,
            'service'             => [
                'code' => 'nl300',
                'name' => 'noname',
            ],
            'physical_properties' => [
                'height' => 1,
                'width'  => 2,
                'length' => 3,
                'volume' => 4,
                'weight' => 5,
            ],
            'options'             => [
                [
                    'name' => 'plx name me',
                    'code' => 'somecode',
                ],
            ],
            'customs'             => [
                'content_type'   => Customs::CONTENT_TYPE_DOCUMENTS,
                'invoice_number' => 'V01C3',
                'incoterm'       => Customs::INCOTERM_DUTY_DELIVERY_UNPAID,
                'non_delivery'   => Customs::NON_DELIVERY_ABANDON,
                'items'          => [
                    [
                        'sku'                 => '01284ASD',
                        'description'         => 'priceless Ming vase from some dynasty',
                        'quantity'            => 12,
                        'hs_code'             => '9801.00.60',
                        'origin_country_code' => 'CN',
                        'item_value'          => [
                            'amount'   => 100000000,
                            'currency' => 'USD',
                        ],
                    ],
                ],
            ],
        ], $this->shipmentTransformer->getAttributes($this->shipment));
    }

    /** @test */
    public function testTransform()
    {
        $this->assertEquals(
            [
                'id'         => 'shipment-id',
                'type'       => 'shipments',
                'attributes' => [
                    'recipient_address'   => [
                        'street_1'             => 'First Street',
                        'street_2'             => 'Second Street',
                        'street_number'        => 69,
                        'street_number_suffix' => 'x',
                        'postal_code'          => '1337OP',
                        'city'                 => 'Felicity',
                        'region_code'          => 'NH',
                        'country_code'         => 'NL',
                        'first_name'           => 'Jane',
                        'last_name'            => 'Doe',
                        'company'              => 'Experts Exchange',
                        'email'                => 'john@expertsexchange.com',
                        'phone_number'         => '1337-9001',
                    ],
                    'sender_address'      => [
                        'street_1'             => 'First Street',
                        'street_2'             => 'Second Street',
                        'street_number'        => 69,
                        'street_number_suffix' => 'x',
                        'postal_code'          => '1337OP',
                        'city'                 => 'Felicity',
                        'region_code'          => 'NH',
                        'country_code'         => 'NL',
                        'first_name'           => 'Jane',
                        'last_name'            => 'Doe',
                        'company'              => 'Experts Exchange',
                        'email'                => 'john@expertsexchange.com',
                        'phone_number'         => '1337-9001',
                    ],
                    'pickup_location'     => [
                        'code'    => 'aaaa',
                        'address' => [
                            'street_1'             => 'First Street',
                            'street_2'             => 'Second Street',
                            'street_number'        => 69,
                            'street_number_suffix' => 'x',
                            'postal_code'          => '1337OP',
                            'city'                 => 'Felicity',
                            'region_code'          => 'NH',
                            'country_code'         => 'NL',
                            'first_name'           => 'Jane',
                            'last_name'            => 'Doe',
                            'company'              => 'Experts Exchange',
                            'email'                => 'john@expertsexchange.com',
                            'phone_number'         => '1337-9001',
                        ],
                    ],
                    'description'         => 'descending ription',
                    'price'               => [
                        'amount'   => 123,
                        'currency' => 'EUR',
                    ],
                    'insurance'           => [
                        'amount'   => 456,
                        'currency' => 'EUR',
                    ],
                    'barcode'             => '3SBARCODE',
                    'weight'              => 789,
                    'service'             => [
                        'code' => 'nl300',
                        'name' => 'noname',
                    ],
                    'physical_properties' => [
                        'height' => 1,
                        'width'  => 2,
                        'length' => 3,
                        'volume' => 4,
                        'weight' => 5,
                    ],
                    'options'             => [
                        [
                            'name' => 'plx name me',
                            'code' => 'somecode',
                        ],
                    ],
                    'customs'             => [
                        'content_type'   => Customs::CONTENT_TYPE_DOCUMENTS,
                        'invoice_number' => 'V01C3',
                        'incoterm'       => Customs::INCOTERM_DUTY_DELIVERY_UNPAID,
                        'non_delivery'   => Customs::NON_DELIVERY_ABANDON,
                        'items'          => [
                            [
                                'sku'                 => '01284ASD',
                                'description'         => 'priceless Ming vase from some dynasty',
                                'quantity'            => 12,
                                'hs_code'             => '9801.00.60',
                                'origin_country_code' => 'CN',
                                'item_value'          => [
                                    'amount'   => 100000000,
                                    'currency' => 'USD',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            $this->shipmentTransformer->transform($this->shipment)
        );
    }

    public function testTransformInvalidModel()
    {
        $this->expectException(TransformerException::class);
        $this->shipmentTransformer->transform(new \stdClass());
    }
}
