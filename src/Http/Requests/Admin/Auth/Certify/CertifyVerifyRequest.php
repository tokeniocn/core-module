<?php

namespace Modules\Core\Http\Requests\Admin\Auth\Permission;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Models\Frontend\UserCertify;

/**
 * Class CertifyVerifyRequest.
 */
class CertifyVerifyRequest extends FormRequest
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
            'status' => 'in:' . implode(',', array_keys(UserCertify::STATUS_MAP))
        ];
    }
}
