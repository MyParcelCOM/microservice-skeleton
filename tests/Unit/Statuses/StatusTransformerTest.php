<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Tests\Unit\Statuses;

use Mockery;
use MyParcelCom\JsonApi\Interfaces\UrlGeneratorInterface;
use MyParcelCom\JsonApi\Transformers\TransformerException;
use MyParcelCom\JsonApi\Transformers\TransformerFactory;
use MyParcelCom\Microservice\Statuses\Status;
use MyParcelCom\Microservice\Statuses\StatusTransformer;
use MyParcelCom\Microservice\Tests\TestCase;

class StatusTransformerTest extends TestCase
{
    /** @var StatusTransformer */
    private $statusTransformer;

    /** @var Status */
    private $status;

    public function setUp()
    {
        parent::setUp();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = Mockery::mock(UrlGeneratorInterface::class, ['route' => 'url']);
        /** @var TransformerFactory $transformerFactory */
        $transformerFactory = Mockery::mock(TransformerFactory::class);

        $this->statusTransformer = (new StatusTransformer($transformerFactory))
            ->setUrlGenerator($urlGenerator);
        $this->status = Mockery::mock(Status::class, [
            'getId'          => 'w',
            'getCode'        => 'u',
            'getDescription' => 'b',
            'getTimestamp'   => 888,
        ]);
    }

    public function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testGetId()
    {
        $this->assertEquals(
            'w',
            $this->statusTransformer->getId($this->status),
            'Failed getting model id.'
        );
    }

    public function testGetAttributes()
    {
        $this->assertEquals([
            'myparcelcom_code' => 'u',
            'description'      => 'b',
            'timestamp'        => 888,
        ], $this->statusTransformer->getAttributes($this->status));
    }

    public function testGetIdWithInvalidModel()
    {
        $this->expectException(TransformerException::class);
        $this->statusTransformer->getId(Mockery::mock(\stdClass::class));
    }

    public function testGetAttributesWithInvalidModel()
    {
        $this->expectException(TransformerException::class);
        $this->statusTransformer->getAttributes(Mockery::mock(\stdClass::class));
    }
}
