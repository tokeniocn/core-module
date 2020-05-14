<?php

namespace Modules\Core\Http\Requests\Frontend\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPayPasswordRequest extends FormRequest
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
            'password' => ['required', 'regex:/^\d{6}$/', 'confirmed'],
            'sms' => ['required'],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
        ];
    }
}
