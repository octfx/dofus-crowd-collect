<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserController extends Controller
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

    public function index()
    {
        $users = User::query()->whereHas('collections', static function (Builder $query) {
            $query->where('public', true);
        })->get();

        return view('collection.users.index', [
            'users' => $users
        ]);
    }
}
