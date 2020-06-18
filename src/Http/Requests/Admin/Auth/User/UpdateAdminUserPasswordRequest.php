<?php


namespace Modules\Core\Http\Requests\Admin\Auth\User;


use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminUserPasswordRequest  extends FormRequest
{
    public function rules()
    {
        return [
            'password' => 'required|regex:/\w{6,20}/',
        ];
    }

    public function messages()
    {
        return [
            'password.required' => '请输入新密码',
            'password.regex' => '新密码长度在6-20位长度之间',
        ];
    }

    public function authorize()
    {
        return $this->user()->isAdmin();
    }
}
