<?php

namespace App\Models;

use App\Enums\LeadStatusEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Lead extends Model
{
    use HasUuids;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'leads';

    protected $fillable = [
        'source',
        'interest',
        'status',
        'contact_id',
    ];

    protected $casts = [
        'status'     => LeadStatusEnum::class,
        'created_at' => 'datetime:d-m-Y H:i:s',
        'updated_at' => 'datetime:d-m-Y H:i:s',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function opportunities(): HasMany
    {
        return $this->hasMany(Opportunity::class);
    }
}
