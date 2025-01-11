@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/my-page.css') }}">
@endsection

@section('content')
<div class="my-page__content">
    <div class="user__group">
        <div class="user__items">
            <div class="user-image">
                @if($user->profile_image)
                <img src="{{ Storage::url($user->profile_image) }}" alt="profile-image">
                @else
                <div class="default-image"></div>
                @endif
            </div>
            <div class="user-name">{{ $user->name }}</div>
        </div>
        <a href="{{ route('profile.create') }}" class="profile__link">プロフィールを編集</a>
    </div>
    <div class="product__group">
        <div class="my-page-list">
            <a href="{{ route('my-page', ['keyword' => request('keyword'), 'page' => 'sell']) }}"
                class="list-item {{ request('page') === 'sell' || request('page') === null ? 'active' : ''}}">出品した商品</a>
            <a href="{{ route('my-page', ['keyword' => request('keyword'), 'page' => 'buy'])  }}" class="list-item {{ request('page') === 'buy' ? 'active' : ''}}">購入した商品</a>
            @if (request('keyword'))
            <a href="{{ route('my-page', ['page' => 'sell'])}}" class="reset-btn">検索をリセットする</a>
            @endif
        </div>
        @if(!$products->isEmpty() || !$purchases->isEmpty())
        <div class="product-list">
            @if(request('page') === 'sell' || request('page') === null)
            @foreach($products as $product)
            <div class="product-card">
                <a href="{{ route('product.show', $product->id) }}">
                <div class="product-image">
                    <img src="{{ Storage::url($product->image) }}" alt="product-image">
                </div>
            </a>
            <a href="{{ route('product.show', $product->id) }}">
                <div class="product-name">{{ $product->name }}</div>
            </a>
        </div>
        @endforeach
        @elseif(request('page') === 'buy')
        @foreach($purchases as $purchase)
        <div class="product-card">
            <a href="{{ route('product.show', $purchase->product->id) }}">
                <div class="product-image">
                    <img src="{{ storage::url($purchase->product->image) }}" alt="product-image">
                </div>
            </a>
            <a href="{{ route('product.show', $purchase->product->id) }}">
                <div class="product-name">{{ $purchase->product->name }}</div>
            </a>
        </div>
        @endforeach
        @endif
    </div>
    @else
    <div class="empty-message">該当する商品がありません。</div>
    @endif
</div>
</div>
@endsection