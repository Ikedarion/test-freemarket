@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

@section('content')
<div class="purchase__content">
    <form action="" method="post" class="purchase-form">
        <div class="left">
            <div class="left-group">
                <div class="group-images">
                    <div class="image">
                        <img src="{{ Storage::url($product->image) }}" alt="product-image">
                    </div>
                    <div class="product-items">
                        <div class="product-name">
                            {{ $product->name }}
                        </div>
                        <div class="product-price">
                            ￥{{ number_format($product->price) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="left-group">
                <label class="purchase-form__label">支払い方法</label>
                <div class="purchase-form__select">
                    <i class="fas fa-caret-down"></i>
                    <select name="payment_method" id="payment-method">
                        <option value="" hidden>選択する</option>
                        <option value="カード払い">カード払い</option>
                        <option value="コンビニ払い">コンビニ払い</option>
                    </select>
                </div>
            </div>
            <div class="left-group">
                <div class="group-items">
                    <label class="purchase-form__label">配送先</label>
                    <a href="{{ route('address', $product->id) }}" class="address__link">変更する</a>
                </div>
                <div class="group-addresses">
                    <div class="postal_code">〒{{ $shipping_address ? $shipping_address->postal_code : '' }}</div>
                    <div class="address_building_name">
                        {{ $shipping_address ?$shipping_address->address : '' }}{{ $shipping_address ?$shipping_address->building_name : '' }}
                    </div>
                </div>
            </div>
        </div>
        <div class="right">
            <div>
                <div class="right-group">
                    <div class="product__heading">商品代金</div>
                    <div class="product__text">￥{{ number_format($product->price) }}</div>
                </div>
            </div>
            <div>
                <div class="right-group">
                    <div class="product__heading">支払い方法</div>
                    <div class="product__text payment-method"></div>
                </div>
            </div>
            <input type="submit" class="payment-btn" value="購入する">
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentMethod = document.querySelector('.product__text.payment-method');
        const selectPaymentMethod = document.getElementById('payment-method');

        selectPaymentMethod.addEventListener('change', function(event) {
            paymentMethod.textContent = selectPaymentMethod.value;
        });
    });
</script>
@endpush