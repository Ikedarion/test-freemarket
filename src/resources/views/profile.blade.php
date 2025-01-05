@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile__content">
    <h2>プロフィール設定</h2>
    <form action="{{ route('store.profile', $user->id ) }}" method="post" class="profile-form" enctype="multipart/form-data">
        @csrf
        <div class="profile-image__group">
            @if($user->profile_image || old('image', $user->profile_image))
            <img src="{{ Storage::url($user->profile_image) }}"
                alt="profile-image" class="image" id="profile-image-preview">
            @else
            <div class="image-default"></div>
            @endif
            <label for="image" class="profile-image__label">
                {{ isset($user) && $user->profile_image ? '画像を変更する' : '画像を選択する' }}
            </label>
            <p id="file-name" class="file-name">{{ old('image') ? basename(old('image')) : ''}}</p>
            <input type="file" name="image" id="image" style="display: none;">
        </div>
        @error('image')
        <div class="error">
            {{ $message }}
        </div>
        @enderror

        <div class="profile-form__group">
            <label for="name" class="profile-form__label">ユーザー名</label>
            <input name="name" id="name" type="text" value="{{ old('name', $user->name ?? '') }}" class="profile-form__input">
        </div>
        @error('name')
        <div class="error">
            {{ $message }}
        </div>
        @enderror

        <div class="profile-form__group">
            <label for="postal-code" class="profile-form__label">郵便番号</label>
            <input name="postal_code" id="postal-code" type="text" value="{{ old('postal_code', $shipping_address->postal_code ?? '') }}" class="profile-form__input">
        </div>
        @error('postal_code')
        <div class="error">
            {{ $message }}
        </div>
        @enderror

        <div class="profile-form__group">
            <label for="address" class="profile-form__label">住所</label>
            <input name="address" id="address" type="text" value="{{ old('address', $shipping_address->address ?? '') }}" class="profile-form__input">
        </div>
        @error('address')
        <div class="error">
            {{ $message }}
        </div>
        @enderror

        <div class="profile-form__group">
            <label for="building-name" class="profile-form__label">建物名</label>
            <input name="building_name" id="building-name" type="text" value="{{ old('building_name', $shipping_address->building_name ?? '') }}" class="profile-form__input">
        </div>
        @error('building_name')
        <div class="error">
            {{ $message }}
        </div>
        @enderror

        <input type="submit" class="profile-form-btn" value="更新する">
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageButton = document.querySelector('.profile-image__label');
        const imageInput = document.getElementById('image');
        const defaultImageDiv = document.querySelector('.profile-image__group .image-default');

        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            var fileName = event.target.files[0] ? event.target.files[0].name : '';
            document.getElementById('file-name').textContent = fileName;

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {

                    const existingImagePreview = document.querySelector('.profile-image__group img');

                    if (existingImagePreview) {
                        existingImagePreview.remove();
                    }

                    const newImagePreview = document.createElement('img');
                    newImagePreview.src = e.target.result;
                    newImagePreview.classList.add('image');
                    document.querySelector('.profile-image__group').insertBefore(newImagePreview, document.querySelector('.profile-image__group label'));

                    if (defaultImageDiv) {
                        defaultImageDiv.remove();
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush