<?php

namespace App\Models;

use App\Enums\{LeadStatusEnum, PriorityEnum, SegmentEnum, ServiceTypeEnum};
use App\ValueObjects\{Address, Phone};
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Lead extends Model
{
    use HasUuids;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'leads';

    protected $fillable = [
        'name',
        'contact_type',
        'phone',
        'email',
        'company_name',
        'position',
        'segment',
        'services',
        'interests',
        'observation',
        'source',
        'lead_source_details',
        'priority',
        'status',
        'cep',
        'address',
    ];

    protected $casts = [
        'address'        => 'array',
        'tags'           => 'array',
        'interests'      => 'array',
        'utm_parameters' => 'array',

        'status'   => LeadStatusEnum::class,
        'priority' => PriorityEnum::class,
        'segment'  => SegmentEnum::class,
        'services' => ServiceTypeEnum::class,
    ];

    protected $hidden = [
        'deleted_at',
    ];

    /**
     * Getter para transformar array em objeto Address.
     */
    public function getAddressObjectAttribute(): ?Address
    {
        return Address::fromArray($this->address);
    }

    /**
     * Setter para armazenar o objeto Address como JSON.
     */
    public function setAddressObjectAttribute(Address $address): void
    {
        $this->attributes['address'] = json_encode($address->toArray());
    }

    public function setPhoneAttribute($phone)
    {
        $this->attributes['phone'] = Phone::fromString($phone)->__toString();
    }
}
