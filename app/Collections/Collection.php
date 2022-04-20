<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Collections;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as LaravelCollection;
use MyParcelCom\JsonApi\Traits\TimestampsTrait;
use MyParcelCom\Microservice\Model\Json\AddressJson;
use MyParcelCom\Microservice\Model\Json\ContactJson;

/**
 * @property string      id
 * @property string      name
 * @property string      myparcelcom_collection_id
 * @property Carbon      collection_time_from
 * @property Carbon      collection_time_to
 * @property AddressJson address_json
 * @property ContactJson contact_json
 * @property Carbon      created_at
 * @property string|null tracking_code
 * @property Carbon|null registered_at
 */
class Collection extends Model
{
    use TimestampsTrait;

    /**
     * Setting this property to an empty array allows assignment of all properties through the constructor.
     *
     * @var array
     */
    protected $guarded = [];

    /** @var array */
    protected $casts = [
        'address_json'         => AddressJson::class,
        'contact_json'         => ContactJson::class,
        'collection_time_from' => 'datetime',
        'collection_time_to'   => 'datetime',
    ];

    public ?LaravelCollection $files = null;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getMyparcelcomCollectionId(): string
    {
        return $this->myparcelcom_collection_id;
    }

    /**
     * @param string $myparcelcomCollectionId
     * @return $this
     */
    public function setMyparcelcomCollectionId(string $myparcelcomCollectionId): self
    {
        $this->myparcelcom_collection_id = $myparcelcomCollectionId;

        return $this;
    }

    /**
     * @return Carbon
     */
    public function getCollectionTimeFrom(): Carbon
    {
        return $this->collection_time_from;
    }

    /**
     * @param Carbon $collectionTimeFrom
     * @return $this
     */
    public function setCollectionTimeFrom(Carbon $collectionTimeFrom): self
    {
        $this->collection_time_from = $collectionTimeFrom;

        return $this;
    }

    /**
     * @return Carbon
     */
    public function getCollectionTimeTo(): Carbon
    {
        return $this->collection_time_to;
    }

    /**
     * @param Carbon $collectionTimeTo
     * @return $this
     */
    public function setCollectionTimeTo(Carbon $collectionTimeTo): self
    {
        $this->collection_time_to = $collectionTimeTo;

        return $this;
    }

    /**
     * @return AddressJson
     */
    public function getAddressJson(): AddressJson
    {
        return $this->address_json;
    }

    /**
     * @param AddressJson $addressJson
     * @return $this
     */
    public function setAddressJson(AddressJson $addressJson): self
    {
        $this->address_json = $addressJson;

        return $this;
    }

    /**
     * @return ContactJson
     */
    public function getContactJson(): ContactJson
    {
        return $this->contact_json;
    }

    /**
     * @param ContactJson $contactJson
     * @return $this
     */
    public function setContactJson(ContactJson $contactJson): self
    {
        $this->contact_json = $contactJson;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTrackingCode(): ?string
    {
        return $this->tracking_code;
    }

    /**
     * @param string|null $trackingCode
     * @return $this
     */
    public function setTrackingCode(?string $trackingCode): self
    {
        $this->tracking_code = $trackingCode;

        return $this;
    }

    /**
     * @return Carbon|null
     */
    public function getRegisteredAt(): ?Carbon
    {
        return $this->registered_at;
    }

    /**
     * @param Carbon $registeredAt
     * @return $this
     */
    public function setRegisteredAt(Carbon $registeredAt): self
    {
        $this->registered_at = $registeredAt;

        return $this;
    }

    /**
     * @return LaravelCollection|null
     */
    public function getFiles(): ?LaravelCollection
    {
        return $this->files;
    }

    /**
     * @param LaravelCollection|null $files
     * @return $this
     */
    public function setFiles(?LaravelCollection $files): self
    {
        $this->files = $files;

        return $this;
    }
}
