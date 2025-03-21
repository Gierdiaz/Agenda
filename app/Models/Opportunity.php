<?php

namespace App\Models;

use App\Enums\OpportunityStatusEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Opportunity extends Model
{
    use HasUuids;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'opportunities';

    protected $fillable = [
        'lead_id',
        'title',
        'description',
        'value',
        'status',
    ];

    protected $casts = [
        'status'     => OpportunityStatusEnum::class,
        'value'      => 'decimal:2',
        'created_at' => 'datetime:d-m-Y H:i:s',
        'updated_at' => 'datetime:d-m-Y H:i:s',
    ];

    protected $hidden = ['deleted_at'];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
