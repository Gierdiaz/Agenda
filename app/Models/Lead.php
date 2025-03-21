<?php

namespace App\Models;

use App\Enums\LeadStatusEnum;
use App\Enums\ServiceTypeEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasUuids;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'leads';

    protected $fillable = [
        'contact_id',
        'segment',
        'services',
        'observation',
        'priority',
        'status',

    ];

    protected $casts = [
        'status' => LeadStatusEnum::class,
        'services' => ServiceTypeEnum::class,
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
