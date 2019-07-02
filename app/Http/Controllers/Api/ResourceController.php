<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ResourceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Perform a search for a resource
     *
     * @param  Request  $request
     *
     * @return Collection
     */
    public function search(Request $request): Collection
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
        ]);

        return Resource::query()->where('name', 'like', $data['name'].'%')->get();
    }
}
