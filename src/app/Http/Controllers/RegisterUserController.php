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
use Illuminate\Foundation\Auth\EmailVerificationRequest;



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

        $user = $creator->create($request->validated());

        event(new Registered($user));

        return redirect()->route('login');
    }

    public function login(LoginRequest $request) {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            if ($user->email_verified_at === null) {
                return redirect()->route('verification.notice');
            }
            return redirect()->intended('/');
        }

        return back()->with('error', 'ログイン情報が登録されていません。');
    }

    public function verify(EmailVerificationRequest $request)
    {
        $user = $request->user();

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            $request->fulfill();
            Auth::login($user);
        } else {
            return redirect()->route('home')->with('message', 'すでに認証済みです');
        }

        if ($user->is_first_login) {
            $user->update(['is_first_login' => false]);
            return redirect()->route('profile.create');
        }

        return redirect()->intended('/');
    }
}
