<?php

namespace App\Models;

use App\Models\Collection\Collection;
use App\Models\Collection\CollectionContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Resource extends Model
{
    protected $fillable = [
        'name',
    ];

    protected $with = [
        'type'
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(ResourceType::class);
    }

    public function collections(): HasManyThrough
    {
        return $this->hasManyThrough(Collection::class, CollectionContent::class);
    }
}
