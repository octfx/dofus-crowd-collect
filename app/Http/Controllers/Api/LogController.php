<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Collection\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a new collection log
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'collection_id' => ['required', 'integer', 'exists:collections,id'],
            'resource_id' => ['required', 'integer', 'exists:resources,id'],
            'update_amount' => ['required', 'integer', 'min:1', 'max:10000'],
        ]);


        /** @var Collection $collection */
        $collection = Collection::findOrFail($data['collection_id']);
        $log = $collection->logs()->create([
            'resource_id' => $data['resource_id'],
            'user_id' => Auth::id(),
            'amount' => $data['update_amount'],
        ]);

        return response()->json($log);
    }
}
