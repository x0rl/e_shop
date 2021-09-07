<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AdminActionRequest extends FormRequest
{
    /**
     * Остановить валидацию после первой неуспешной проверки.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isAdmin() and $this->user()->id !== $this->id; //todo в роуте посредник с такой же проверкой хмхм
        //return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'=>'required|integer',
            'action'=>['required', 'string', 'regex:#^ban$|^unban$|^upToAdmin$|^downToUser$#']
        ];
    }
}
