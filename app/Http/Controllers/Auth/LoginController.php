<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dash';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Explicitly set the identifier
     *
     * @return string
     */
    public function username(): string
    {
        return 'username';
    }

    /**
     * Generate a new API Key on Login
     *
     * @param  Request  $request
     * @param  User  $user
     */
    protected function authenticated(Request $request, User $user)
    {
        error_log("test");
        if ($user->name_slug == null) {
            $user->name_slug = Str::slug($user->username);
        }

        $user->rollApiKey();

        $user->save();

        return response()->redirectTo($this->redirectTo);
    }
}
