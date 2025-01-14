@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="top-page__content">
    <div class="top-page-list">
        <a href="{{ route('home', ['keyword' => request('keyword'), 'page' => null]) }}" class="{{ request('page') === null ? 'active' : '' }}">おすすめ</a>
        <a href="{{ route('home', ['keyword' => request('keyword'), 'page' => 'my-list']) }}" class="{{ request('page') === 'my-list' ? 'active' : '' }}">マイリスト</a>
        @if (request('keyword'))
        <a href="{{ route('home')}}" class="reset-btn">検索をリセットする</a>
        @endif
    </div>

    @if($products->isEmpty())
    <div class="empty-message">該当する商品がありません。</div>
    @else
    <div class="product-list">
        @foreach($products as $product)
        <div class="product-card">
            @if (in_array($product->status, ['売却済み', '取引中', '取り下げ']))
            <a href="#">
                <div class="product-image">
                    <div class="sold-out-overlay">
                        <span class="sold-out-text">Sold Out</span>
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
        @endif
    </div>
</div>
@endsection