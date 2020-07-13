<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Collection\Collection;
use App\Models\Collection\CollectionContent;
use App\Models\Resource;
use App\Models\User;
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
        return Collection::public()->paginate(10);
    }

    /**
     * All personal collections
     *
     * @return mixed
     */
    public function indexPersonal()
    {
        return Auth::user()->collections()->orderByDesc('public')->paginate(10);
    }

    /**
     * Returns a single collection
     *
     * @param  Collection  $collection
     * @return JsonResponse
     */
    public function show(Collection $collection): JsonResponse
    {
        abort_if(!$collection->public && (Auth::id() !== $collection->user_id), 403);

        return response()->json($collection);
    }

    /**
     * Returns collections for a user
     *
     * @param  User  $user
     * @return JsonResponse
     */
    public function showUser(User $user): JsonResponse
    {
        return response()->json($user->collections()->where('public', true)->get());
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
            'content.*.name' => ['required', 'string', 'max:255', 'exists:resources'],
            'content.*.amount' => ['required', 'integer', 'max:10000', 'min:1'],
            'content.*.note' => ['nullable', 'string', 'max:250'],
        ]);

        /** @var Collection $collection */
        $collection = Collection::create([
            'name' => $data['name'],
            'user_id' => Auth::id(),
            'public' => $data['public'],
        ]);

        foreach ($data['content'] as $content) {
            $resource = Resource::firstOrCreate(['name' => $content['name']]);

            /** @var CollectionContent $contentModel */
            $contentModel = $collection->content()->create([
                'resource_id' => $resource->id,
                'amount' => $content['amount'],
            ]);


            if (isset($content['note']) && !empty($content['note'])) {
                $contentModel->note()->create([
                    'content' => $content['note']
                ]);
            }
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

        return abort(403);
    }

    public function update(Request $request, Collection $collection)
    {
        $data = $request->validate([
            'public' => ['required', 'boolean']
        ]);

        if ($collection->user->id === $request->user()->id) {
            $collection->public = $data['public'] === true;
            $collection->save();

            return response()->json($collection);
        }

        return abort(403);
    }
}
