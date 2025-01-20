<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'image' => 'mimes:jpeg,png',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'ユーザー名を入力してください。',
            'image.mimes' => '画像はjpeg形式またはpng形式を選択してください。',
        ];
    }
}
