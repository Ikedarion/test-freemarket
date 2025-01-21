@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
@endsection

@section('content')
<div class="auth__content">
    <h2 class="auth__header">パスワードリセット</h2>
    <form action="{{ route('password.update') }}" method="post" class="form">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
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
            <label for="password" class="form__label">新しいパスワード</label>
            <input name="password" type="password" id="password" class="form__input" required>
        </div>

        @error('password')
        <div class="error">
            {{ $message }}
        </div>
        @enderror

        <div class="form__group">
            <label for="password_confirmation" class="form__label">新しいパスワード（確認用）</label>
            <input name="password_confirmation" type="password" id="password_confirmation" class="form__input" required>
        </div>

        <div class="form__group-link">
            <input type="submit" value="パスワードを更新" class="form__btn">
        </div>
    </form>
</div>
@endsection