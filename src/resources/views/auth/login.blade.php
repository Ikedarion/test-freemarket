<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreeMarket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sanitize.css/13.0.0/sanitize.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css')}}">
</head>

<header class="header">
    <div class="header-item">
        <a class="home__link" href="/"></a>
        <img src="{{ asset('logo_image/logo.svg') }}" alt="header-image" class="header-logo">
    </div>
</header>
<div class="main__content">
    @if(session('error'))
    <div class="error-message">
        {{ session('error') }}
    </div>
    @endif
    @if(session('success'))
    <div class="success-message">
        {{ session('success') }}
    </div>
    @endif
    <div class="login__content">
        <h2>ログイン</h2>
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
                <a href="" class="login__link">パスワードをお忘れの方はこちら</a>
            </div>
        </form>
    </div>
</div>