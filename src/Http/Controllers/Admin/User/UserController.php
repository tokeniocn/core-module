<?php

namespace Modules\Core\Http\Controllers\Admin\User;

use Modules\Core\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('core::admin.user.index');
    }
}