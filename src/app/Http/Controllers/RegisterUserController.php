<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class RegisterUserController extends Controller
{
    public function store(
        RegisterRequest $request,
        CreatesNewUsers $creator
    ) {
        if (config('fortify.lowercase_usernames')) {
            $request->merge([
                Fortify::username() => Str::lower($request->{Fortify::username()}),
            ]);
        }

        $user = $creator->create($request->all());
        event(new Registered($user));

        return redirect()->route('profile.create');
    }

    public function login(LoginRequest $request) {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            //** if ($user->email_verified_at === null) {
            //**   return redirect()->route('verification.notice');
            //** }
        }

        return back()->with('error', 'ログイン情報が登録されていません。');
    }
}
