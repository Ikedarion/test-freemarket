<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'description' => 'required|max:255',
            'image' => 'required|mimes:jpeg,png',
            'condition' => 'required',
            'price' => 'required|integer|min:0',
            'category_id' => 'required|array|min:1',
            'category_id.*' => 'exists:categories,id',
            'color_id' => 'required',
            'brand_name' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください。',
            'description.required' => '商品説明を入力してください。',
            'description.max' => '商品説明は255文字以内で入力してください。',
            'image.required' => '画像を選択してください。',
            'image.mimes' => '画像はjpeg形式またはpng形式を選択してください。',
            'condition.required' => '商品状態を選択してください。',
            'price.required' => '商品価格を入力してください。',
            'price.integer' => '商品価格は数値型で入力してください。',
            'price.min' => '商品価格は0円以上で入力してください。',
            'color_id.required' => 'カラーを選択してください。',
            'category_id.required' => 'カテゴリーを選択してください。',
            'category_id.array' => 'カテゴリーのデータ形式が正しくありません。',
            'category_id.min' => 'カテゴリーを1つ以上選択してください。',
            'category_id.*.exists' => '選択したカテゴリーが見つかりません。',
            'brand_name.required' => 'ブランド名をを入力してください。',
        ];
    }
}
