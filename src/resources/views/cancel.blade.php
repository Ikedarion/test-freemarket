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
        margin-bottom: 45px;
        letter-spacing: 1px;
        color: #333;
    }

    .heading p {
        margin-bottom: 10px;
    }

    .home-link {
        text-decoration: none;
        padding: 6px 16px;
        border-radius: 5px;
        font-size: 13px;
        font-weight: bold;
        display: inline-block;
        margin-right: 10px;
        transition: background-color 0.2s;
        cursor: pointer;
        border: 1.3px solid #FF5555;
    }

    .home-link {
        background-color: #FF5555;
        color: white;
    }
</style>


@section('content')
<div class="cancel__content">
    <div class="cancel__container">
        <div class="heading">
            <p>お支払いがキャンセルされました。</p>
            <p>再度お試しください。</p>
        </div>
        <div class="link">
            <a class="home-link" href="/">ホーム</a>
        </div>
    </div>
</div>
@endsection