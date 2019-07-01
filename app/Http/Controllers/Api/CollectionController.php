<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Collection\Collection;
use App\Models\Resource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
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
     * All public colelctions
     *
     * @return mixed
     */
    public function index()
    {
        return Collection::public()->paginate(100);
    }

    /**
     * All personal collections
     *
     * @return mixed
     */
    public function indexPersonal()
    {
        return Auth::user()->collections()->paginate(100);
    }

    /**
     * Store a new collection
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'public' => ['required', 'boolean'],
            'content' => ['required', 'array'],
            'content.*.name' => ['required', 'string', 'max:255'],
            'content.*.amount' => ['required', 'integer', 'max:10000', 'min:1']
        ]);

        /** @var Collection $collection */
        $collection = Collection::create([
            'name' => $data['name'],
            'user_id' => Auth::id(),
            'public' => $data['public'],
        ]);

        foreach ($data['content'] as $content) {
            $resource = Resource::firstOrCreate(['name' => $content['name']]);
            $collection->content()->create([
                'resource_id' => $resource->id,
                'amount' => $content['amount'],
            ]);
        }

        return response()->json($collection);
    }

    /**
     * Destroy a collection
     *
     * @param  Request  $request
     * @param  Collection  $collection
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, Collection $collection)
    {
        if ($collection->user->id === $request->user()->id) {
            return response()->json($collection->delete());
        }

        return response()->json([], 401);
    }
}