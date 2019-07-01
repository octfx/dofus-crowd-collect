<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
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
     * @return Renderable
     */
    public function index(): Renderable
    {
        $logs = Auth::user()->collectionLogs()->with('collection')->get();

        return view('log.index')->with([
            'logs' => $logs,
        ]);
    }
}
