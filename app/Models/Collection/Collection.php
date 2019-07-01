<?php

namespace App\Models\Collection;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Collection extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'public'
    ];

    protected $with = [
        'logs',
        'content',
        'user'
    ];

    protected $withCount = [
        'content'
    ];

    protected $hidden = [
        'user_id',
        'updated_at',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function content(): HasMany
    {
        return $this->hasMany(CollectionContent::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(CollectionLog::class)->orderByDesc('id');
    }

    public function scopePublic(Builder $query)
    {
        return $query->where('public', 1);
    }
}
