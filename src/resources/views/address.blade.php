@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="address__content">
    <h2 class="address__heading">住所の変更</h2>
    <form action="{{ route('address.update', $shipping_address ? $shipping_address->postal_code : '') }}" method="post" class="address-form">
        @csrf
        @method('PATCH')
        <label for="postal_code" class="address-form__label">郵便番号</label>
        <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $shipping_address ? $shipping_address->postal_code : '') }}" class="address-form__input">
        @error('postal_code')
        <div class="error">
            {{ $message }}
        </div>
        @enderror

        <label for="address" class="address-form__label">住所</label>
        <input type="text" name="address" id="address" value="{{ old('address', $shipping_address ? $shipping_address->address : '') }}" class="address-form__input">
        @error('address')
        <div class="error">
            {{ $message }}
        </div>
        @enderror

        <label for="building_name" class="address-form__label">建物名</label>
        <input type="text" name="building_name" id="building_name" value="{{ old('building_name', $shipping_address ? $shipping_address->building_name : '') }}" class="address-form__input">
        @error('building_name')
        <div class="error">
            {{ $message }}
        </div>
        @enderror

        <input type="submit" class="address-form-btn" value="更新する">
    </form>
</div>
@endsection