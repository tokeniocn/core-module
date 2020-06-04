<?php

namespace Modules\Core\Http\Controllers\Frontend\Auth;

use Modules\Core\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function index()
    {
        return view('core::frontend.register.index');
    }
}
