<?php

namespace App\Models\Collection;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollectionContent extends Model
{
    protected $fillable = [
        'name',
        'amount',
        'resource_id'
    ];

    protected $with = [
        'resource',
    ];

    protected $appends = [
        'sum',
    ];

    protected $casts = [
        'collection_id' => 'integer',
        'resource_id' => 'integer',
        'amount' => 'integer',
        'sum' => 'integer',
    ];

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(CollectionLog::class, 'resource_id');
    }

    public function getSumAttribute(): int
    {
        return $this->logs()->sum('amount');
    }
}
