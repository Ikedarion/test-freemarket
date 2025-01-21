@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
@endsection

@section('content')
<div class="auth__content">
    <h2 class="auth__header">ログイン</h2>
    <form action="/login" method="post" class="form">
        @csrf
        <div class="form__group">
            <label for="name-email" class="form__label">ユーザー名/メールアドレス</label>
            <input name="email" type="email" id="name-email" value="{{ old('email') }}" class="form__input">
        </div>
        @error('email')
        <div class="error">
            {{ $message }}
        </div>
        @enderror

        <div class="form__group">
            <label for="password" class="form__label">パスワード</label>
            <input name="password" type="password" id="password" class="form__input">
        </div>
        @error('password')
        <div class="error">
            {{ $message }}
        </div>
        @enderror

        <div class="form__group-link">
            <input type="submit" value="ログインする" class="form__btn">
            <a href="/register" class="login__link">会員登録はこちら</a>
            <a href="{{ route('password.request') }}" class="password-reset__link">パスワードをお忘れの方はこちら</a>
        </div>
    </form>
</div>
@endsection