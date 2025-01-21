@extends('layouts.app')
<style>
    .cancel__content {
        width: 100%;
        height: 100%;
        padding: 5% 0 15px;
        box-sizing: border-box;
        color: #464646;
    }

    .cancel__container {
        margin: 0 auto;
        text-align: center;
    }

    .heading {
        padding-top: 90px;
        font-size: 20px;
        margin-bottom: 40px;
        letter-spacing: 1px;
        color: #333333;
    }

    h3 {
        margin-bottom: 20px;
    }

    .heading p {
        font-size: 17px;
    }

    .home-link,
    .my-page-link {
        text-decoration: none;
        padding: 6px 16px;
        border-radius: 5px;
        font-size: 13px;
        font-weight: bold;
        display: inline-block;
        margin-right: 20px;
        transition: background-color 0.2s;
        cursor: pointer;
        border: 1.3px solid #FF5555;
    }

    .home-link {
        background-color: #FF5555;
        color: white;
    }

    .my-page-link {
        color: #FF5555;
    }

    p {
        line-height: 1.7;
    }

    .message {
        margin-bottom: 40px;
    }
</style>


@section('content')
<div class="cancel__content">
    <div class="cancel__container">
        <div class="heading">
            <h3>ご購入ありがとうございます</h3>
            <p>決済が正常に完了しました。</p>
        </div>
        <div class="message">
            <p>このたびは商品をご購入いただき、誠にありがとうございます。</p>
            <p>購入いただいた商品は、マイページにて詳細をご確認いただけます。</p>
        </div>
        <div class="link">
            <a class="home-link" href="/">ホーム</a>
            <a class="my-page-link" href="/mypage">マイページ</a>
        </div>
    </div>
</div>
@endsection