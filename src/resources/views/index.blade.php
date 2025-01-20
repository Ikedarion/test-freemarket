@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="top-page__content">
    <div class="top-page-list">
        <a href="{{ route('home', ['keyword' => request('keyword'), 'tab' => null]) }}" class="{{ request('tab') === null ? 'active' : '' }}">おすすめ</a>
        <a href="{{ route('home', ['keyword' => request('keyword'), 'tab' => 'mylist']) }}" class="{{ request('tab') === 'mylist' ? 'active' : '' }}">マイリスト</a>
        @if (request('keyword'))
        <a href="{{ route('home')}}" class="reset-btn">検索をリセットする</a>
        @endif
    </div>

    @if (request('tab') === 'mylist' && !Auth::check())
    <div class="empty-message">
    </div>
    @elseif($products->isEmpty())
    <div class="empty-message">
        @auth
        該当する商品がありません。
        @endauth
    </div>
    @else
    <div class="product-list">
        @foreach($products as $product)
        <div class="product-card">
            @if (in_array($product->status, ['売却済み', '取引中', '取り下げ']))
            <a href="#">
                <div class="product-image">
                    <div class="sold-overlay">
                        <span class="sold-label">Sold</span>
                    </div>
                    <img src="{{ Storage::url($product->image) }}" alt="product-image" class="image">
                </div>
            </a>
            <a href="#">
                <div class="product-name">{{ $product->name }}</div>
            </a>
            @else
            <a href="{{ route('product.show', $product->id) }}">
                <div class="product-image">
                    <img src="{{ Storage::url($product->image) }}" alt="product-image" class="image">
                </div>
            </a>
            <a href="{{ route('product.show', $product->id) }}">
                <div class="product-name">{{ $product->name }}</div>
            </a>
            @endif
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection