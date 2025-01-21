@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
@endsection

@section('content')
<div class="auth__content">
    <h3 class="auth__header">パスワードをお忘れの方</h3>
    <p class="auth__description">
        パスワードリセット用のリンクをお送りします。
        <br>ご登録のメールアドレスをご入力ください。
    </p>
    <form action="{{ route('password.email') }}" method="post" class="form">
        @csrf
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

        <div class="form__group-link">
            <input type="submit" value="送信する" class="form__btn">
        </div>
    </form>
</div>
@endsection