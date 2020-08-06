<?php


namespace Modules\Core\Http\Controllers\Admin\Auth\User;


use Illuminate\Routing\Controller;

class OperateLogController extends Controller
{

    public function index(){

        return view('core::admin.auth.operate.index');

    }

}
