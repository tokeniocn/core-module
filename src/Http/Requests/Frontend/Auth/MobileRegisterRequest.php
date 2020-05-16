<?php

namespace Modules\Core\Http\Requests\Frontend\Auth;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class MobileRegisterRequest extends FormRequest
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
            'username' => [
                Rule::requiredIf(function() {
                    return empty($this->email) && empty($this->mobile);
                }),
                'nullable',
                'string',
                Rule::unique(User::table())
            ],
            'password' => ['string', 'min:8', 'max:30'],
            'email' => [
                'nullable',
                'email',
                Rule::unique(User::table())
            ],
            'mobile' => [
                'nullable',
                Rule::phone()->country(config('core::register.mobile.countries', ['CN'])),
                Rule::unique(User::table())
            ],
            'code' => ['required_with:mobile']
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
