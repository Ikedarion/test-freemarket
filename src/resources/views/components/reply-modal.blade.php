<div class="modal" id="modal{{ $comment->user_id }}">
    <div class="modal">
        <div class="modal__inner">
            <div class="modal__content">
                <form action="" class="reply-form">
                    <label for="reply">返信を入力</label>
                    <textarea name="reply" id="reply">{{ old('reply' . $comment->user_id) }}</textarea>
                    @error('reply' . $comment->user_id)
                    <div class="error">
                        {{ $message }}
                    </div>
                    @enderror
                    <input type="submit" class="reply-btn" value="送信する">
                </form>
            </div>
        </div>
    </div>
</div>