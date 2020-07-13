<?php

namespace App\Models;

use App\Models\Collection\Collection;
use App\Models\Collection\CollectionLog;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'api_token', 'name_slug'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
        'created_at',
    ];

    protected $withCount = [
        'collections',
        'collectionsPublic'
    ];

    public function getRouteKeyName()
    {
        return 'name_slug';
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value)
    {
        return $this->where('name_slug', $value)->first() ?? abort(404);
    }

    public function collections(): HasMany
    {
        return $this->hasMany(Collection::class);
    }

    public function collectionsPublic(): HasMany
    {
        return $this->hasMany(Collection::class)->scopes(['public']);
    }

    public function collectionLogs(): HasMany
    {
        return $this->hasMany(CollectionLog::class);
    }

    public function rollApiKey(): void
    {
        $this->api_token = Str::random(60);
        $this->save();
    }
}
