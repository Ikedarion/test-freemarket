@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="top-page__content">
    <div class="top-page-list">
        <a href="{{ route('home') }}" class="{{ request('page') === null ? 'active' : '' }}">おすすめ</a>
        <a href="{{ route('home', ['page' => 'my-list']) }}" class="{{ request('page') === 'my-list' ? 'active' : '' }}">マイリスト</a>
    </div>

    <div class="product-list">
        @foreach($products as $product)
        <div class="product-card">
            <a href="{{ route('product.show', $product->id) }}">
                <div class="product-image">
                    <img src="{{ Storage::url($product->image) }}" alt="product-image" class="image">
                </div>
            </a>
            <a href="{{ route('product.show', $product->id) }}">
                <div class="product-name">{{ $product->name }}</div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection