<?php

namespace App\Models\Collection;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectionLog extends Model
{
    protected $fillable = [
        'user_id',
        'resource_id',
        'amount',
    ];

    protected $with = [
        'user',
        'resource',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }
}
