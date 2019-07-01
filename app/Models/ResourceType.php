<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResourceType extends Model
{
    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }
}
