@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/products/detail.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

@section('content')
<div class="detail__content">
    @if (in_array($product->status, ['売却済み', '取引中', '取り下げ']))
    <div class="product-image">
        <div class="sold-overlay">
            <span class="sold-label">Sold</span>
        </div>
        <img src="{{ Storage::url($product->image) }}" alt="product-image" class="image">
    </div>
    @else
    <div class="product-image">
        <img src="{{ Storage::url($product->image) }}" alt="product-image">
    </div>
    @endif
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
                        <i class="far fa-star gray"></i>
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
            @if (in_array($product->status, ['売却済み', '取引中', '取り下げ']))
            <a href="#" class="payment__link">現在購入できません</a>
            @else
            <a href="{{ route('purchase', $product->id) }}" class="payment__link">購入手続きへ</a>
            @endif
            <div class="product__heading">商品説明</div>
            <div class="product-text">カラー：
                <span>{{ $product->color->name }}</span>
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
        <div class="comment-form">
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
                        @if($comment->reply)
                        <div class="comment__reply">
                            <p>{{ $comment->updated_at }}</p>
                            <span>➥ {{ $comment->reply }}</span>
                        </div>
                        @endif
                        @if($isOwner && $comment->reply === null)
                        <p><a class="modal__link" href="#" data-modal-id="modal-{{ $comment->user_id }}">返信する</a></p>
                        @endif
                        <x-reply-modal :comment="$comment" />
                    </div>
                </div>
                @endforeach
                @else
                <div class="comment__group">コメントはまだありません。</div>
                @endif
            </div>
            <form action="{{ route('comment.store') }}" method="post" class="comment-form">
                @csrf
                <div>
                    <div for="comment" class="comment__label">商品へのコメント</div>
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <textarea name="content" class="comment__textarea">{{ old('content') }}</textarea>
                    @error('content')
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
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const openButtons = document.querySelectorAll('.modal__link');
        const modals = document.querySelectorAll('.modal');

        openButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const targetModalId = button.getAttribute('data-modal-id');
                const targetModal = document.getElementById(targetModalId);

                if (targetModal) {
                    targetModal.classList.add('open');
                }
            });
        });
        modals.forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.remove('open')
                }
            });

            const form = modal.querySelector('.reply-form');
            if (form) {
                form.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        });
    });
</script>
@endpush