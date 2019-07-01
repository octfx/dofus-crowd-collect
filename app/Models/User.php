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
        'username', 'password', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $withCount = [
        'collections'
    ];

    public function collections(): HasMany
    {
        return $this->hasMany(Collection::class);
    }

    public function collectionLogs(): HasMany
    {
        return $this->hasMany(CollectionLog::class);
    }

    public function rollApiKey(): void
    {
        $this->save([
            'api_token' => Str::random(60)
        ]);
    }
}
