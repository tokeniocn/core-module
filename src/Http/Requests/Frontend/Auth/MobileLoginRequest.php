<?php

namespace Modules\Core\Http\Requests\Frontend\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LangleyFoxall\LaravelNISTPasswordRules\PasswordRules;

/**
 * Class MobileLoginRequest
 */
class MobileLoginRequest extends FormRequest
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
                'nullable',
                'string',
                Rule::unique(User::table())
            ],
            'mobile' => [
                'required',
                Rule::phone()->country(config('core::register.mobile.countries', ['CN'])),
            ],
            'code' => 'required',
            'device' => 'string'
        ];
    }
}
