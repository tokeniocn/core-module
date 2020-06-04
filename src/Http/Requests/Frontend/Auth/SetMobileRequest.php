<?php

namespace Modules\Core\Http\Requests\Frontend\Auth;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

<<<<<<< HEAD:src/Http/Requests/Frontend/Auth/NotifyEmailRequest.php
class NotifyEmailRequest extends FormRequest
=======
class SetMobileRequest extends FormRequest
>>>>>>> master:src/Http/Requests/Frontend/Auth/SetMobileRequest.php
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
<<<<<<< HEAD:src/Http/Requests/Frontend/Auth/NotifyEmailRequest.php
            'email' => [
                'required',
                'email',
            ],
            'type' => [
                'required',
                'string',
            ]
=======
            'mobile' => ['required', 'regex:/^1[3456789]\d{9}$/'],
>>>>>>> master:src/Http/Requests/Frontend/Auth/SetMobileRequest.php
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
