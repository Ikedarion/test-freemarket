<div class="modal {{ $errors->has('reply' . $comment->user_id) ? 'open' : ''}}" id="modal-{{ $comment->user_id }}">
    <div class="modal__inner">
        <div class="modal__content">
            <div class="modal__group">
                <div class="modal__image">
                    @if($comment->user->profile_image)
                    <div class="modal__user-image">
                        <img src="{{ Storage::url($comment->user->profile_image) }}" alt="">
                    </div>
                    @else
                    <div class="modal__default-image"></div>
                    @endif
                    <span class="modal__user-name">{{ $comment->user->name ?? '匿名' }}</span>
                </div>
                <div class="modal__user-text">{{ $comment->content }}</div>
                <span class="modal-timestamp">{{ $comment->created_at->format('Y-m-d H:i') }}</span>
            </div>
            <form action="{{ route('comment.reply', $comment->id) }}" method="POST" class="reply-form">
                @csrf
                @method('PATCH')
                <label for="reply" class="reply-form__label">返信を入力</label>
                <textarea name="reply{{ $comment->user_id }}" class="reply-form__textarea">{{ old('reply' . $comment->user_id) }}</textarea>
                @error('reply' . $comment->user_id)
                <div class="error">
                    {{ $message }}
                </div>
                @enderror
                <input type="submit" class="reply-form__submit" value="送信する">
            </form>
        </div>
    </div>
</div>