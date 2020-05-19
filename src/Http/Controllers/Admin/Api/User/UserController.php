<?php

namespace Modules\Core\Http\Controllers\Admin\Api\User;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Services\Frontend\UserService;

class UserController extends Controller
{

    public function index(Request $request, UserService $userService)
    {
        $result = $userService->all([], [
            'orderBy' => ['id', 'desc'],
            'paginate' => true
        ]);

        return $result;
    }
}
