<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Collections;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection as LaravelCollection;
use MyParcelCom\JsonApi\Traits\TimestampsTrait;
use MyParcelCom\Microservice\Model\Json\AddressJson;
use MyParcelCom\Microservice\Model\Json\ContactJson;

/**
 * @property string            id
 * @property string|null       name
 * @property string            myparcelcom_collection_id
 * @property Carbon            collection_time_from
 * @property Carbon            collection_time_to
 * @property AddressJson       address_json
 * @property ContactJson       contact_json
 * @property Carbon            created_at
 * @property string|null       tracking_code
 * @property Carbon|null       registered_at
 * @property LaravelCollection shipments
 */
class Collection extends Model
{
    use TimestampsTrait;
    use HasFactory;

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

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @deprecated
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @deprecated
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMyparcelcomCollectionId(): string
    {
        return $this->myparcelcom_collection_id;
    }

    public function setMyparcelcomCollectionId(string $myparcelcomCollectionId): self
    {
        $this->myparcelcom_collection_id = $myparcelcomCollectionId;

        return $this;
    }

    public function getCollectionTimeFrom(): Carbon
    {
        return $this->collection_time_from;
    }

    public function setCollectionTimeFrom(Carbon $collectionTimeFrom): self
    {
        $this->collection_time_from = $collectionTimeFrom;

        return $this;
    }

    public function getCollectionTimeTo(): Carbon
    {
        return $this->collection_time_to;
    }

    public function setCollectionTimeTo(Carbon $collectionTimeTo): self
    {
        $this->collection_time_to = $collectionTimeTo;

        return $this;
    }

    public function getAddressJson(): AddressJson
    {
        return $this->address_json;
    }

    public function setAddressJson(AddressJson $addressJson): self
    {
        $this->address_json = $addressJson;

        return $this;
    }

    public function getContactJson(): ContactJson
    {
        return $this->contact_json;
    }

    public function setContactJson(ContactJson $contactJson): self
    {
        $this->contact_json = $contactJson;

        return $this;
    }

    public function getTrackingCode(): ?string
    {
        return $this->tracking_code;
    }

    public function setTrackingCode(?string $trackingCode): self
    {
        $this->tracking_code = $trackingCode;

        return $this;
    }

    public function getRegisteredAt(): ?Carbon
    {
        return $this->registered_at;
    }

    public function setRegisteredAt(Carbon $registeredAt): self
    {
        $this->registered_at = $registeredAt;

        return $this;
    }

    public function getFiles(): ?LaravelCollection
    {
        return $this->files;
    }

    public function setFiles(?LaravelCollection $files): self
    {
        $this->files = $files;

        return $this;
    }

    public function getShipments(): LaravelCollection
    {
        // return $this->shipments;
    }

    public function shipments(): HasMany
    {
        // return $this->hasMany(Shipment::class);
    }
}
