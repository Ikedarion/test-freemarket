@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/products/detail.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

@section('content')
<div class="detail__content">
    <div class="product-image">
        <img src="{{ Storage::url($product->image) }}" alt="product-image">
    </div>
    <div class="product-detail">
        <div class="detail-content">
            <div class="product-name">{{ $product->name }}</div>
            <div class="product-brand-name">{{ $product->brand_name }}</div>
            <div class="product-price">
                <span>￥</span>{{ number_format($product->price) }}<span class="product-tax">(税込)</span>
            </div>
            <div class="icons">
                <form action="{{ route('like', $product->id) }}" method="post" class="star-icon">
                    @csrf
                    <button class="likes-btn">
                        @if($isLiked)
                        <i class="fas fa-star yellow"></i>
                        @else
                        <i class="far fa-star"></i>
                        @endif
                    </button>
                    <p>{{ $product->likedByUsers->count() }}</p>
                </form>
                <div class="comment-icon">
                    <div class="comment-btn">
                        <i class="far fa-comment"></i>
                    </div>
                    <p>{{ $product->comments->count() }}</p>
                </div>
            </div>
            <a href="{{ route('purchase', $product->id) }}" class="payment__link">購入手続きへ</a>
            <div class="product__heading">商品説明</div>
            <div class="product-text">カラー：
                <span>{{ $product->color }}</span>
            </div>
            <div class="product-text">{{ $product->description }}</div>
            <div class="product__heading">商品の情報</div>
            <div class="product-text">
                <div class="product__label">カテゴリー</div>
                @foreach ($product->categories as $category)
                <span class="category-name">{{ $category->name }}
                </span>
                @endforeach
            </div>
            <div class="product-text">
                <div class="product__label">商品の状態</div>
                <span>{{ $product->condition }}</span>
            </div>
        </div>
        <form action="{{ route('comment.store') }}" method="post" class="comment-form">
            @csrf
            <div class="comment__heading">コメント({{ $product->comments->count() }})</div>
            <div class="user-items">
                @if($product->user->profile_image)
                <div class="user-image">
                    <img src="{{ Storage::url($product->user->profile_image) }}" alt="">
                </div>
                @else
                <div class="image-default"></div>
                @endif
                <span class="user-name">{{ $product->user->name }}</span>
            </div>
            <div class="comment-list">
                @if($product->comments->isNotEmpty())
                @foreach($product->comments as $comment)
                <div class="comment__group">
                    <div class="comment-items">
                        <span class="comment__user-item">
                            @if($comment->user->profile_image)
                            <div class="comment__user-image">
                                <img src="{{ Storage::url($comment->user->profile_image) }}" alt="">
                            </div>
                            @else
                            <div class="comment__default-image"></div>
                            @endif
                            <span class="comment__user-name">{{ $comment->user->name ?? '匿名' }}</span>
                        </span>
                        <span class="comment-timestamp">{{ $comment->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                    <div class="comment__text">
                        <div>{{ $comment->content }}</div>
                        <div>{{ $comment->reply }}</div>
                        @if($isOwner && $comment->reply === null)
                        <p><a class="modal__link" href="#modal{{ $comment->user_id }}">返信する</a></p>
                        @endif
                        <x-reply-modal :comment="$comment"/>
                    </div>
                </div>
                @endforeach
                @else
                <div class="comment__group">コメントはまだありません。</div>
                @endif
            </div>
            <div>
                <div for="comment" class="comment__label">商品へのコメント</div>
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <textarea name="content" class="comment__textarea">{{ old('comment') }}</textarea>
                @error('comment')
                <div class="error">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div>
                <input type="submit" class="comment__submit" value="コメントを送信する">
            </div>
        </form>
    </div>
</div>
@endsection