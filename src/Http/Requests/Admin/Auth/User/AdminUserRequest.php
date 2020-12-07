<?php


namespace Modules\Core\Http\Requests\Admin\Auth\User;


use Illuminate\Foundation\Http\FormRequest;

class AdminUserRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->isAdmin();
    }

    public function rules()
    {
        return [
            'username' => ['required','min:4'],
            'rules_id' => ['required'],
            'active' => ['required'],
        ];
    }

}
