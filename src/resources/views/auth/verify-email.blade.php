@extends('layouts.auth')
<style>
    .verify__content {
        width: 60%;
        height: 100%;
        text-align: center;
        padding: 50px 3%;
        box-sizing: border-box;
        color: #464646;
        margin: 0 auto;
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

@section('content')
<div class="verify__content">
    <h2>メール認証のお願い</h2>
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
@endsection