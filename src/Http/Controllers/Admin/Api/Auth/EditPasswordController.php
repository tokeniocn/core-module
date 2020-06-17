<?php


namespace Modules\Core\Http\Controllers\Admin\Api\Auth;

use App\Models\AdminUser;
use Modules\Core\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EditPasswordController extends Controller
{

    public function editPassword(Request $request)
    {

        $request->validate([
            'password' => 'required|digits_between:6,20',
        ], [
            'password.required' => '请输入新密码',
            'password.digits_between' => '新密码长度在6-20位长度之间',
        ]);

        $user = $request->user();
        $user->password = Hash::make($request->password);
        $user->save();
        return $user;
    }

}
