<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreeMarket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sanitize.css/13.0.0/sanitize.min.css">
    <link rel="stylesheet" href="{{ asset('css/common.css')}}">
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
</head>

<header class="header">
    <div class="header-item">
        <a class="home__link" href="/"></a>
        <img src="{{ asset('logo_image/logo.svg') }}" alt="header-image" class="header-logo">
    </div>
</header>
<div class="main__content">
    <div class="register__content">
        <h2 class="register__header">会員登録</h2>
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
                <input name="password" type="password" id="password" value="{{ old('password') }}" class="form__input">
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
                <input name="password_confirmation" type="password" id="password_confirmation" value="{{ old('password_confirmation') }}" class="form__input">
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
</div>