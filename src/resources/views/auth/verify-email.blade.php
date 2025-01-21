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
<style>
    .verify__content {
        width: 60%;
        height: 100%;
        text-align: center;
        padding: 50px 3%;
        box-sizing: border-box;
        color: #464646;
    }

    h1 {
        margin-bottom: 30px;
    }

    .alert {
        margin-top: 30px;
        color: #FF5555;
    }

    .btn {
        margin-top: 60px;
        padding: 8px 10px;
        background-color: #FF5555;
        color: white;
        border: none;
        border-radius: 3px;
    }
</style>

<header class="header">
    <div class="header-item">
        <a class="home__link" href="/"></a>
        <img src="{{ asset('logo_image/logo.svg') }}" alt="header-image" class="header-logo">
    </div>
</header>
<div class="main__content">
    <div class="verify__content">
        <h1>メール認証のお願い</h1>
        <p>認証メールのリンクをクリックして、認証を完了してください。</p>
        <p>見つからない場合は、迷惑メールフォルダをご確認ください。</p>

        @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success">
            新しい認証リンクが送信されました。
        </div>
        @endif

        <form action="{{ route('verification.send') }}" method="post">
            @csrf
            <button class="btn btn-primary">認証メールを再送信</button>
        </form>
    </div>
</div>