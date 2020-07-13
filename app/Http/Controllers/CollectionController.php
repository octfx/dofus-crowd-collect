<?php


namespace App\Http\Controllers;


use App\Models\Collection\Collection;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
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
        $this->middleware('auth');
    }

    /**
     * Create form
     *
     * @return Renderable
     */
    public function create(): Renderable
    {
        return view('collection.create');
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(): Renderable
    {
        return view('collection.public.index');
    }

    /**
     * Returns a single collection
     *
     * @param  Collection  $collection
     * @return Renderable
     */
    public function show(Collection $collection): Renderable
    {
        abort_if(!$collection->public && (Auth::id() !== $collection->user_id), 403);

        return view('collection.show', [
            'collection' => $collection,
        ]);
    }

    /**
     * Returns a single collection
     *
     * @param  User  $user
     * @return Renderable
     */
    public function showUser(User $user): Renderable
    {
        return view('collection.users.show', [
            'user' => $user,
        ]);
    }
}
