<?php

namespace Modules\Core\Http\Requests\Frontend\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Models\Frontend\UserBank;

class UserBankRequest extends FormRequest
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
            'value' => 'required',
            'bank' => 'required|in:' . implode(',', array_keys(UserBank::$bankTypeMap))
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
