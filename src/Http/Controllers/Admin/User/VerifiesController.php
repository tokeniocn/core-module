<?php


namespace Modules\Core\Http\Controllers\Admin\User;


use Modules\Core\Http\Controllers\Controller;

class VerifiesController extends Controller
{

    public function index(){

        return view('core::admin.user.index');
    }

}
