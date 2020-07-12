<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function getApiToken()
    {
        abort_if(Auth::guest(), 401);

        return response()->json([
            'api_token' => Auth::user()->api_token,
        ]);
    }
}
