<?php

namespace App\Http\Requests;

use App\Models\SubCategory;
use Illuminate\Foundation\Http\FormRequest;

class AddProductRequest extends FormRequest
{
    /**
     * Надстройка экземпляра валидатора.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function($validator) {
            if (! SubCategory::find($this->sub_category_id)) {
                $validator->errors()->add('subCategory', "Категории $this->sub_category_id не существует");
            }
        });
    }

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
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required|string|min:5|max:45',
            'description'=>'required|string|min:5|max:1000',
            'price'=>'required|integer',
            'quantity'=>'required|integer',
            'sub_category_id' => 'required|integer'
        ];
    }
}
