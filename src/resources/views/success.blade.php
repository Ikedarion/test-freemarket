@extends('layouts.app')
<style>
    .cancel__content {
        width: 100%;
        height: 100%;
        padding: 4% 0 20px;
        box-sizing: border-box;
        color: #464646;
        background-color: #f9f9f9;
    }

    .cancel__container {
        margin: 0 auto;
        max-width: 700px;
        text-align: center;
        padding: 20px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .heading {
        padding-top: 30px;
        font-size: 24px;
        margin-bottom: 20px;
        letter-spacing: 0.5px;
        color: #333333;
    }

    .heading h3 {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .heading p {
        font-size: 18px;
        color: #666666;
        margin-top: 0;
    }

    .message {
        margin-bottom: 30px;
        font-size: 16px;
        line-height: 1.7;
        color: #555555;
    }

    .link {
        margin: 30px 0;
    }

    .home-link,
    .my-page-link {
        text-decoration: none;
        padding: 12px 24px;
        border-radius: 5px;
        font-size: 14px;
        font-weight: bold;
        display: inline-block;
        margin-right: 20px;
        transition: background-color 0.3s, color 0.3s, transform 0.2s;
        cursor: pointer;
        border: 2px solid transparent;
        text-transform: uppercase;
    }

    .home-link {
        background-color: #FF5555;
        color: white;
        border-color: #FF5555;
    }

    .my-page-link {
        color: #FF5555;
        border-color: #FF5555;
    }

    p {
        line-height: 1.7;
    }

    /* レスポンシブ対応 */
    @media (max-width: 768px) {
        .heading h3 {
            font-size: 24px;
        }

        .heading p {
            font-size: 15px;
        }

        .cancel__content {
            padding: 10% 0 15px;
        }

        .cancel__container {
            padding: 15px;
        }

        .home-link,
        .my-page-link {
            padding: 10px 20px;
            font-size: 13px;
        }

        .message {
            font-size: 14px;
        }
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