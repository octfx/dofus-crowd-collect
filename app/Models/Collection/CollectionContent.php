<?php

namespace App\Models\Collection;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class CollectionContent extends Model
{
    protected $fillable = [
        'name',
        'amount',
        'resource_id'
    ];

    protected $with = [
        'resource',
        'note'
    ];

    protected $appends = [
        'sum',
        'vueKey',
    ];

    protected $casts = [
        'collection_id' => 'integer',
        'resource_id' => 'integer',
        'amount' => 'integer',
        'sum' => 'integer',
    ];

    public function getVueKeyAttribute(): string  {
        return Str::slug($this->updated_at);
    }

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
        return $this->hasMany(CollectionLog::class, 'resource_id', 'resource_id');
    }

    public function note(): HasOne
    {
        return $this->hasOne(CollectionContentNote::class);
    }

    public function getSumAttribute(): int
    {
        return $this->logs()->sum('amount');
    }
}
