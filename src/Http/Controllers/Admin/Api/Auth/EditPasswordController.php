<?php


namespace Modules\Core\Http\Controllers\Admin\Api\Auth;


use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Admin\Auth\User\UpdateAdminUserPasswordRequest;

class EditPasswordController extends Controller
{

    public function editPassword(UpdateAdminUserPasswordRequest $request)
    {

        $user = $request->user();
        $user->password = $request->password;
        $user->save();
        return $user;
    }

}
