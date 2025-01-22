@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/products/create.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

@section('content')
<div class="product__content">
    <h2 class="product__heading">商品の出品</h2>
    <form action="{{ route('product.store') }}" class="product-form" enctype="multipart/form-data" method="post">
        @csrf
        <div class="product__group">
            <label for="image" class="product__label">商品の画像</label>
            <div class="product__group-inner">
                <div class="product-image">
                    <img src="" class="image">
                </div>
                <span id="file-name" class="file-name">{{ old('image') ? basename(old('image')) : ''}}</span>
                <label for="image" class="profile-image__label">
                    画像を選択する
                </label>
                <input type="file" name="image" id="image" style="display: none;">
            </div>
        </div>
        @error('image')
        <div class="error">
            {{ $message }}
        </div>
        @enderror

        <div class="heading">商品の詳細</div>
        <div class="product__group">
            <label for="category" class="product__label">カテゴリー</label>
            @foreach($categories as $category)
            <input id="{{ $category->id }}" name="category_id[]" type="checkbox" class="category-checkbox" style="display: none;" value="{{ $category->id }}" {{ in_array($category->id, old('category_id', [])) ? 'checked' : ''}}>
            <label class="category" for="{{ $category->id }}">{{ $category->name }}</label>
            @endforeach
        </div>
        @error('category_id')
        <div class="error">
            {{ $message }}
        </div>
        @enderror

        <div class="product__group">
            <label for="color" class="product__label">カラー</label>
            <i class="fas fa-caret-down"></i>
            <select name="color_id" id="color" class="color">
                <option value="" hidden>選択する</option>
                @foreach($colors as $color)
                <option value="{{ $color->id }}" {{ old('color_id') == $color ? 'selected' : '' }}>{{ $color->name }}</option>
                @endforeach
            </select>
        </div>
        @error('color_id')
        <div class="error">
            {{ $message }}
        </div>
        @enderror

        <div class="product__group">
            <label for="condition" class="product__label">商品の状態</label>
            <i class="fas fa-caret-down"></i>
            <select name="condition" id="condition" class="condition">
                <option value="" hidden>選択する</option>
                @foreach($conditions as $condition)
                <option value="{{ $condition }}" {{ old('condition') == $condition ? 'selected' : '' }}>{{ $condition }}</option>
                @endforeach
            </select>
        </div>
        @error('condition')
        <div class="error">
            {{ $message }}
        </div>
        @enderror

        <div class="heading">商品名と説明</div>
        <div class="product__group">
            <label for="name" class="product__label">商品名</label>
            <input name="name" type="text" class="product-name" value="{{ old('name') }}">
        </div>
        @error('name')
        <div class="error">
            {{ $message }}
        </div>
        @enderror

        <div class="product__group">
            <label for="brand_name" class="product__label">ブランド名</label>
            <input name="brand_name" type="text" class="brand-name" value="{{ old('brand_name') }}">
        </div>
        @error('brand_name')
        <div class="error">
            {{ $message }}
        </div>
        @enderror

        <div class="product__group">
            <label for="description" class="product__label">商品の説明</label>
            <textarea type="text" name="description" id="description" class="description">{{ old('description') }}</textarea>
        </div>
        @error('description')
        <div class="error">
            {{ $message }}
        </div>
        @enderror

        <div class="product__group">
            <label for="price" class="product__label">販売価格</label>
            <input name="price" type="text" class="price" placeholder="￥" value="{{ old('price') }}">
        </div>
        @error('price')
        <div class="error">
            {{ $message }}
        </div>
        @enderror

        <div class="product__group">
            <input type="submit" class="sell-btn" value="出品する">
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image');
        const productImage = document.querySelector('.product-image');

        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            var filename = file ? file.name : '';
            document.getElementById('file-name').textContent = filename;

            if (file) {
                productImage.style.display = 'block';

                const reader = new FileReader();
                reader.onload = function(e) {
                    const existingImagePreview = document.querySelector('.product-image img');

                    if (existingImagePreview) {
                        existingImagePreview.src = e.target.result;
                    } else {
                        const newImage = document.createElement('img');
                        newImage.src = e.target.result;
                        newImage.classList.add('open');
                        document.querySelector('.product-image').appendChild(newImage);
                    }
                };
                reader.readAsDataURL(file);
            } else {
                productImage.style.display = 'none';
            }
        });
    });
</script>
@endpush