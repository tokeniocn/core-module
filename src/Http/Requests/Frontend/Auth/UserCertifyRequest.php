<?php

namespace Modules\Core\Http\Requests\Frontend\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Models\Frontend\UserCertify;

class UserCertifyRequest extends FormRequest
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
            'obverse' => 'required',
            'reverse' => 'required',
            'name' => 'required',
            'number' => 'required',
            'certify_type' => 'required|in:' . implode(',', array_keys(UserCertify::$certifyTypeMap))
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
