<?php


namespace App\Http\Controllers;


use App\Models\Collection\Collection;
use Illuminate\Contracts\Support\Renderable;

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
        abort_if(!$collection->public, 403);

        return view('collection.show', [
            'collection' => $collection,
        ]);
    }
}
