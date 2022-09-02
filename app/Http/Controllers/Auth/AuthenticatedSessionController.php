<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Models\Logs;
use App\Events\UserLogsEvent;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {


        /* Login */
        $request->authenticate();

        $user = User::where('email', $request->email)->first();
        // Auth::guard('api')->loginUsingId($user->id); //custom guard for api
        event(new UserLogsEvent($user->id, Logs::TYPE_LOGIN, [
            'email'  =>   $request->email,
        ]));
        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function destroy(Request $request)
    {

        $user = User::where('email', Auth::guard('web')->user()->email)->first();
        event(new UserLogsEvent($user->id, Logs::TYPE_LOGOUT, [
            'email'  =>    $user->email,
        ]));

        // Auth::guard('api')->logout();
        Auth::guard('web')->logout();

        $request->session()->regenerate();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
