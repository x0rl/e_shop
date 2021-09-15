<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditAddressRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'city' => 'required|string|max:168',
            'street' => 'required|string|max:132',
            'house' => 'required|integer|max:99999',
            'corp' => 'integer|max:1337|nullable'
        ];
    }
}
