<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_name' => 'required | alpha_dash',
            'company_name' => 'required',
            'price' => 'required | alpha_num',
            'stock' => 'required | alpha_num',
            'comment' => 'alpha_dash',
        ];
    }

    /**
     * 項目名
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'product_name' => '商品名',
            'company_name' => 'メーカー名',
            'price' => 'プライス',
            'stock' => '在庫数',
            'comment' => 'コメント',
        ];
    }

    /**
     * エラーメッセージ
     *
     * @return array
     */
    public function messages() {
        return [
            'product_name.required' => ':attributeは必須項目です。',
            'product_name.alpha_dash' => ':attributeは半角英数字、または全角で入力してください。',
            'company_name.required' => ':attributeは必須項目です。',
            'price.required' => ':attributeは必須項目です。',
            'price.alpha_num' => ':attributeは半角英数字で入力してください。',
            'stock.required' => ':attributeは必須項目です。',
            'stock.alpha_num' => ':attributeは半角英数字で入力してください。',
            'comment.alpha_dash' => ':attributeは半角英数字、または全角で入力してください。',
        ];
    }
}
