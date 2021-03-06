<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Collection\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class LogController extends Controller
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

    public function indexPersonal()
    {
        return Auth::user()->collectionLogs()->with('collection')->orderByDesc('id')->paginate(25);
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
            'resource_id' => [
                'required',
                'integer',
                'exists:resources,id',
                Rule::exists('collection_contents')->where(static function ($query) use ($request) {
                    $query->where('collection_id', $request->get('collection_id'));
                })
            ],
            'update_amount' => ['required', 'integer', 'min:1', 'max:10000'],
        ]);

        /** @var Collection $collection */
        $collection = Collection::query()->where('id', $data['collection_id'])->firstOrFail();

        abort_if($request->user()->id !== $collection->user->id && !$collection->public, 403);

        $amount = $collection->logs()->where('resource_id', $data['resource_id'])->sum('amount');
        $maxAmount = $collection->content()->where('resource_id', $data['resource_id'])->firstOrFail();
        $maxAmount = $maxAmount->amount;

        if ($amount + $data['update_amount'] > $maxAmount) {
            abort(409, "Amount + Update = $amount + ". $data['update_amount']." > $maxAmount");
        }

        $log = $collection->logs()->create([
            'collection_id' => $data['collection_id'],
            'resource_id' => $data['resource_id'],
            'user_id' => Auth::id(),
            'amount' => $data['update_amount'],
        ]);

        $log->load(['user', 'resource']);

        return response()->json($log);
    }
}
