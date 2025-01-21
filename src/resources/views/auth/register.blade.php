@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
@endsection

@section('content')
<div class="auth__content">
    <h2 class="auth__header">会員登録</h2>
    <form action="/register" method="post" class="form">
        @csrf
        <div class="form__group">
            <label for="name" class="form__label">
                ユーザー名
            </label>
            <input name="name" type="text" id="name" value="{{ old('name') }}" class="form__input">
        </div>
        @error('name')
        <div class="error">
            {{ $message }}
        </div>
        @enderror

        <div class="form__group">
            <label for="email" class="form__label">
                メールアドレス
            </label>
            <input name="email" type="email" id="email" value="{{ old('email') }}" class="form__input">
        </div>
        @error('email')
        <div class="error">
            {{ $message }}
        </div>
        @enderror

        <div class="form__group">
            <label for="password" class="form__label">
                パスワード
            </label>
            <input name="password" type="password" id="password" class="form__input">
        </div>
        @error('password')
        <div class="error">
            {{ $message }}
        </div>
        @enderror

        <div class="form__group">
            <label for="password_confirmation" class="form__label">
                確認用パスワード
            </label>
            <input name="password_confirmation" type="password" id="password_confirmation" class="form__input">
        </div>
        @error('password_confirmation')
        <div class="error">
            {{ $message }}
        </div>
        @enderror

        <div class="form__group-link">
            <input type="submit" value="登録する" class="form__btn">
            <a href="/login" class="login__link">ログインはこちら</a>
        </div>
    </form>
</div>
@endsection