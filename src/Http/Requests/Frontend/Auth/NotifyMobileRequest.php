<?php

namespace Modules\Core\Http\Requests\Frontend\Auth;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class NotifyMobileRequest extends FormRequest
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
            'mobile' => [
                'required',
                Rule::phone()->country(config('core::register.mobile.countries', ['CN']))
            ],
            'type' => [
                'required',
                'string',
            ]
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
