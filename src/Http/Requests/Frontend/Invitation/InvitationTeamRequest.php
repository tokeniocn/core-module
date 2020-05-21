<?php

namespace Modules\Core\Http\Requests\Frontend\Invitation;


use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SendContactRequest.
 */
class InvitationTeamRequest extends FormRequest
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
            'level' => 'numeric|between:1,3',
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
